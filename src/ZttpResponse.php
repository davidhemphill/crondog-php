<?php

namespace CronDog;

class ZttpResponse
{
    var $response;

    function __construct($response)
    {
        $this->response = $response;
    }

    function body()
    {
        return (string) $this->response->getBody();
    }

    function json()
    {
        return json_decode($this->response->getBody(), true);
    }

    function header($header)
    {
        return $this->response->getHeaderLine($header);
    }

    function status()
    {
        return $this->response->getStatusCode();
    }

    function __call($method, $args)
    {
        return $this->response->{$method}(...$args);
    }
}
