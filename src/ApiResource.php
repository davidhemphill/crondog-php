<?php

namespace CronDog;

abstract class ApiResource
{
    var $attributes;

    var $response;

    static $endpoints = [
        'dev' => 'http://crondog.dev/api',
        'prod' => 'https://crondog.io/api'
    ];

    static function getUrl($extra = null)
    {
        return static::$endpoints[getenv('CRONDOG_ENV')] . static::$endpoint . $extra;
    }

    static function createRequest($method, $id, $attributes = null)
    {
        if (func_num_args() == 2) {
            $attributes = $id;
            $id = null;
        }

        $options = $method != 'delete' ? $attributes : array_merge($attributes, ['_method' => 'delete']);
        $merged = static::mergeWithCredentials($options);

        return Zttp::accept('application/json')
            ->asJson()
            ->{$method}(static::getUrl($id), $merged);
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
