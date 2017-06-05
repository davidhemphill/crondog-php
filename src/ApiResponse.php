<?php

namespace CronDog;

class ApiResponse
{
    function __construct($response)
    {
        $this->response = $response;
    }

    function __call($method, $params)
    {
        return $this->response->{$method}(...$params);
    }

    function __get($key)
    {
        return $this->response->json()[$key];
    }
}