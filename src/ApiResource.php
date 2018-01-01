<?php

namespace CronDog;

use Zttp\Zttp;
use CronDog\ApiResponse;

abstract class ApiResource
{
    var $attributes;

    var $response;

    static function getUrl($suffix = null)
    {
        return CronDog::getBaseUrl() . static::$endpoint . $suffix;
    }

    static function createRequest($method, ...$args)
    {
        $attributes = [];
        $id = null;

        switch (count($args)) {
            case 1:
                $attributes = $args[0];
                break;
            case 2:
                $id = $args[0];
                $attributes = $args[1];
                break;
        }

        $merged = array_merge($attributes, static::mergeWithCredentials());

        return new ApiResponse(
            Zttp::accept('application/json')
                ->asJson()
                ->{$method}(static::getUrl($id), $merged)
        );
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

    function __construct($attributes = [], $response = null)
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
