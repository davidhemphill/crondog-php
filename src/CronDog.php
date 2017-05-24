<?php

namespace CronDog;

class CronDog
{
    static $apiKey;

    static function setApiKey($apiKey)
    {
        return static::$apiKey = $apiKey;
    }

    static function getApiKey()
    {
        return static::$apiKey;
    }
}