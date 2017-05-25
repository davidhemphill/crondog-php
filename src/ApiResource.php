<?php

namespace CronDog;

abstract class ApiResource
{
    var $attributes;

    var $response;

    static $devBaseUri = 'http://crondog.dev/api';

    static $prodBaseUri = 'https://crondog.io/api';

    static function getUrl($extra = null)
    {
        if (getenv('CRONDOG_ENV') == 'dev') {
            return static::$devBaseUri . static::$endpoint . $extra;
        }

        return static::$prodBaseUri . static::$endpoint . $extra;
    }

    static function createRequest($method, $id, $attributes = null)
    {
        if (func_num_args() == 2) {
            $attributes = $id;
            $id = null;
        }

        return Zttp::accept('application/json')
            ->asJson()
            ->{$method}(static::getUrl($id), static::mergeWithCredentials($attributes));
    }

    static function mergeWithCredentials($something = [], $with = [])
    {
        return array_merge($something, array_merge([
            'api_token' => CronDog::getApiKey()
        ], $with));
    }

    static function createFromResponse($response)
    {
        return new static($response->json(), $response);
    }

    static function createFromArray($item)
    {
        return new static($item);
    }

    function getResponse()
    {
        return $this->response;
    }

    function __construct($attributes, $response = null)
    {
        $this->attributes = $attributes;
        $this->response = $response;
    }

    function __get($key)
    {
        return $this->attributes[$key];
    }

    function toArray()
    {
        return $this->attributes;
    }
}
