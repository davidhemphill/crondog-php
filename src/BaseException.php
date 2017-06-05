<?php

namespace CronDog;

use Exception;

class BaseException extends Exception
{
    function __construct($message, $status, $body, $json) {
        parent::__construct($message);
        $this->status = $status;
        $this->body = $body;
        $this->json = $json;
    }

    function status()
    {
        return $this->status;
    }
}