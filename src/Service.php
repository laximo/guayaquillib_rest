<?php

namespace GuayaquilLib;

use GuayaquilLib\exceptions\AccessDeniedException;
use GuayaquilLib\exceptions\AccessLimitReachedException;
use GuayaquilLib\exceptions\CatalogFeatureNotSupportedExeption;
use GuayaquilLib\exceptions\CatalogNotExistsException;
use GuayaquilLib\exceptions\GroupIsNotSearchableException;
use GuayaquilLib\exceptions\InvalidParameterException;
use GuayaquilLib\exceptions\InvalidRequestException;
use GuayaquilLib\exceptions\NotSupportedException;
use GuayaquilLib\exceptions\OperationNotFoundException;
use GuayaquilLib\exceptions\StandardPartException;
use GuayaquilLib\exceptions\TemporaryUnavailableExeption;
use GuayaquilLib\exceptions\TimeoutException;
use GuayaquilLib\exceptions\TooManyRequestsException;
use GuayaquilLib\exceptions\UnexpectedProblemException;
use GuayaquilLib\exceptions\UnknownCommandException;
use GuayaquilLib\objects\BaseObject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use InvalidArgumentException;
use RuntimeException;
use Psr\Http\Message\ResponseInterface;

class Service
{
    /** @var string */
    private $baseUrl;

    /** @var array<string, string> */
    private $defaultHeaders;

    /** @var Client */
    private $client;

    /** @var string|null */
    private $login;

    /** @var string|null */
    private $password;

    /**
     * @param string|null $login
     * @param string|null $password
     * @param string $baseUrl
     * @param string[]|array<string, string> $defaultHeaders
     * @param Client|null $client
     */
    public function __construct(
        ?string $login = null,
        ?string $password = null,
        string $baseUrl = 'https://ws.laximo.ru/',
        array  $defaultHeaders = [
            'Accept-Language' => 'ru_RU',
            'accept' => 'application/json',
        ],
        Client $client = null
    )
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultHeaders = $this->normalizeHeaders($defaultHeaders);
        $this->login = $login;
        $this->password = $password;
        $this->client = $client ?: new Client([
            'base_uri' => $this->baseUrl . '/',
            'http_errors' => false,
        ]);
    }

    /**
     * Send REST API request using data from Command.
     *
     * URL format: {baseUrl}/{service}/{operation}
     *
     * @param Command $command
     * @param string[]|array<string, string> $headers Additional HTTP headers
     * @return BaseObject
     */
    public function executeCommand(Command $command) : BaseObject
    {
        return $this->request($command);
    }

    /**
     * Send REST API request using data from Command.
     *
     * URL format: {baseUrl}/{service}/{operation}
     *
     * @param Command[] $command
     * @param string[]|array<string, string> $headers Additional HTTP headers
     * @return BaseObject
     */
    public function executeCommands($commands) : array
    {
        if (is_array($commands)) {
            return $this->requestMulti($commands);
        }

        throw new InvalidArgumentException('Command must be an instance of Command or array of Command.');
    }

    /**
    // Start of Selection
    /**
     * @param Command $command
     * @return string
     */
    private function buildUrl(Command $command): string
    {
        $params = $command->getParams();
        if (!empty($params)) {
            // Build query string: key1=value1&key2=value2...
            unset($params['Accept-Language']);

            $queryString = [];
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subvalue) {
                        $queryString[] = urlencode($key) . '=' . urlencode($subvalue);
                    }
                } else {
                    $queryString[] = urlencode($key) . '=' . urlencode($value);
                }
            }

            return sprintf(
                '%s/restApiV1/%s?%s',
                $this->baseUrl,
                rawurlencode($command->getOperation()),
                implode('&', $queryString)
            );
        }
        
        return sprintf(
            '%s/restApiV1/%s',
            $this->baseUrl,
            rawurlencode($command->getOperation())
        );
    }

    /**
     * @param string $method
     * @param string $url
     * @param Command $command
     * @param string[]|array<string, string> $headers
     * @return BaseObject
     */
    private function request(Command $command): BaseObject
    {
        try {
            $url = $this->buildUrl($command);
            $options = $this->buildRequestOptions($command);
            $response = $this->client->request('POST', $url, $options);
        } catch (GuzzleException $exception) {
            throw new RuntimeException('Unable to execute REST request: ' . $exception->getMessage(), 0, $exception);
        }

        return $this->normalizeResponse($command, $response);
    }

    protected function getObject($data, string $className, bool $isArray): BaseObject
    {
        $payload = $isArray ? $data : (is_array($data) ? $data : $data->row ?? $data);
        return new $className($payload);
    }

    /**
     * @param Command[] $commands
     * @param string $method
     * @param string[]|array<string, string> $headers
     * @return array<int, BaseObject>
     */
    private function requestMulti(array $commands): array
    {
        if (count($commands) === 0) {
            return [];
        }

        foreach ($commands as $index => $command) {
            if (!$command instanceof Command) {
                throw new InvalidArgumentException('Each array item must be an instance of Command.');
            }
        }

        $responses = [];
        $errors = [];
        $requestFactory = function () use ($commands) {
            foreach ($commands as $command) {
                yield function () use ($command) {
                    return $this->client->requestAsync(
                        'POST',
                        $this->buildUrl($command),
                        $this->buildRequestOptions($command)
                    );
                };
            };
        };

        $pool = new Pool($this->client, $requestFactory(), [
            'concurrency' => count($commands),
            'fulfilled' => function (ResponseInterface $response, int $index) use (&$responses, $commands): void {
                $responses[$index] = $this->normalizeResponse($commands[$index], $response);
            },
            'rejected' => function ($reason, int $index) use (&$errors): void {
                $errors[$index] = $reason;
            },
        ]);

        $pool->promise()->wait();

        if (count($errors) > 0) {
            ksort($errors);
            $index = array_key_first($errors);
            $reason = $errors[$index];
            $message = is_object($reason) && method_exists($reason, 'getMessage')
                ? $reason->getMessage()
                : (string)$reason;

            throw new RuntimeException(
                sprintf('Unable to execute REST request in parallel for command index %d: %s', $index, $message)
            );
        }

        ksort($responses);

        return array_values($responses);
    }

    /**
     * @param Command $command
     * @param ResponseInterface $response
     * @return BaseObject
     */
    private function normalizeResponse(Command $command, ResponseInterface $response) : BaseObject
    {
        $body = (string)$response->getBody();

        if ($response->getStatusCode() !== 200) {
            $message = 'Unknown error';
            if (!empty($body)) {
                $decoded = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // If new format: {"message": "E_ERROR:details"}
                    $serviceMessage = $decoded['message'] ?? null;
                    if ($serviceMessage && strpos($serviceMessage, 'E_') === 0) {
                        list($reason, $detail) = explode(':', $serviceMessage, 2) + [null, null];
                        switch (trim($reason)) {
                            case 'E_CATALOGNOTEXISTS':
                                throw new CatalogNotExistsException($serviceMessage);
                            case 'E_INVALIDREQUEST':
                                throw new InvalidRequestException($serviceMessage);
                            case 'E_INVALIDPARAMETER':
                                throw new InvalidParameterException($serviceMessage);
                            case 'E_UNKNOWNCOMMAND':
                                throw new UnknownCommandException($serviceMessage);
                            case 'E_ACCESSDENIED':
                                throw new AccessDeniedException($serviceMessage);
                            case 'E_NOTSUPPORTED':
                                throw new NotSupportedException($serviceMessage);
                            case 'E_GROUP_IS_NOT_SEARCHABLE':
                                throw new GroupIsNotSearchableException($serviceMessage);
                            case 'E_TOO_MANY_REQUESTS':
                                throw new TooManyRequestsException($serviceMessage);
                            case 'E_STANDARD_PART_SEARCH':
                                throw new StandardPartException($serviceMessage);
                            case 'E_ACCESSLIMITREACHED':
                                throw new AccessLimitReachedException($serviceMessage);
                            case 'E_CATALOG_FEATURE_NOT_SUPPORTED':
                                throw new CatalogFeatureNotSupportedExeption($serviceMessage);
                            case 'E_OPERATION_NOT_FOUND':
                                throw new OperationNotFoundException($serviceMessage);
                            case 'E_TEMPORARY_UNAVAILABLE':
                                throw new TemporaryUnavailableExeption($serviceMessage);
                            case 'E_TIMEOUT':
                                throw new TimeoutException($serviceMessage);
                            case 'E_UNEXPECTED_PROBLEM':
                                throw new UnexpectedProblemException($serviceMessage);
                        }
                        // If not recognized, throw generic
                        throw new RuntimeException($serviceMessage);
                    }
                    // If not in "E_xxx:detail" format, show raw message
                    $message = $serviceMessage ?: ('HTTP Error ' . $response->getStatusCode());
                } else {
                    $message = 'HTTP Error: ' . $response->getStatusCode() . '. Unable to decode error response: ' . $body;
                }
            } else {
                $message = 'HTTP Error: ' . $response->getStatusCode() . '. Empty error body.';
            }
            throw new RuntimeException($message);
        }

        $parsedData = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->getObject($parsedData, $command->getResponseClassName(), $command->isResponseArray());
        } else {
            throw new RuntimeException('Unable to parse response body: ' . json_last_error_msg());
        }
    }

    /**
     * @param string[]|array<string, string> $headers
     * @return array<string, string>
     */
    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            if (is_string($name) && is_string($value)) {
                $normalized[$name] = $value;
            } elseif (is_string($value) && strpos($value, ':') !== false) {
                list($name, $value) = explode(':', $value, 2);
                $normalized[trim($name)] = trim($value);
            } elseif (is_string($value)) {
                $normalized[$value] = '';
            }
        }

        return $normalized;
    }

    /**
     * @param string $method
     * @param string[] $params
     * @param string[]|array<string, string> $headers
     * @return array<string, mixed>
     */
    private function buildRequestOptions(Command $command): array
    {
        $mergedHeaders = $this->defaultHeaders;
        $queryParams = $command->getParams();

        if (isset($queryParams['Locale'])) {
            $mergedHeaders['Accept-Language'] = (string)$queryParams['Locale'];
            unset($queryParams['Locale']);
        } elseif (isset($queryParams['locale'])) {
            $mergedHeaders['Accept-Language'] = (string)$queryParams['locale'];
            unset($queryParams['locale']);
        } elseif (isset($queryParams['Accept-Language'])) {
            $mergedHeaders['Accept-Language'] = (string)$queryParams['Accept-Language'];
            unset($queryParams['Accept-Language']);
        }

        $options = [
            'headers' => $mergedHeaders,
        ];

        if ($this->login !== null || $this->password !== null) {
            $options['auth'] = [(string)$this->login, (string)$this->password, 'basic'];
        }

        return $options;
    }

}
