<?php


namespace GuayaquilLib\objects;

use Exception;

abstract class BaseObject
{
    /**
     * @param array|null $data
     * @throws Exception
     */
    public function __construct($data = null)
    {
        if (is_null($data)) {
            throw new Exception('Empty data');
        }

        $this->fromJson($data);
    }

    /**
     * @param array $data
     */
    abstract protected function fromJson(array $data);
}