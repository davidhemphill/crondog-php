<?php

namespace CronDog;

class CronDog
{
    static $apiKey;

    static $devBaseUrl = 'http://crondog.test/api/';

    static $prodBaseUrl = 'https://crondog.io/api/';

    static function setDevBaseUrl($url)
    {
        static::$devBaseUrl = $url;
    }

    static function getBaseUrl()
    {
        if (getenv('CRONDOG_ENV') == 'dev') {
            return static::$devBaseUrl;
        }

        return static::$prodBaseUrl;
    }

    static function setApiKey($apiKey)
    {
        return static::$apiKey = $apiKey;
    }

    static function getApiKey()
    {
        return static::$apiKey;
    }
}
