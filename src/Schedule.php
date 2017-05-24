<?php

namespace CronDog;

use GuzzleHttp\Client;

class Schedule
{
    static $devBaseUri = 'http://crondog.dev/api/';

    static $prodBaseUri = 'https://crondog.io/api/';

    static $endpoint = 'schedules';

    var $attributes;

    static function getUrl($extra = null)
    {
        if (getenv('CRONDOG_ENV') == 'dev') {
            return static::$devBaseUri . static::$endpoint . $extra;
        }

        return static::$prodBaseUri . static::$endpoint . $extra;
    }

    static function createRequest()
    {
        return Zttp::accept('application/json')
            ->asJson();
    }

    static function mergeWithCredentials($something = [], $with = [])
    {
        return array_merge($something, array_merge([
            'api_token' => CronDog::getApiKey()
        ], $with));
    }

    static function get($attributes)
    {
        $response = static::createRequest()
            ->get(static::getUrl(), static::mergeWithCredentials($attributes));

        return $response;
    }

    static function find($attributes)
    {
        $id = $attributes['id'];

        return static::createRequest()
            ->get(static::getUrl("/{$id}"), static::mergeWithCredentials($attributes));
    }

    static function create($attributes)
    {
        $response = static::createRequest()
            ->post(static::getUrl(), static::mergeWithCredentials($attributes));

        return $response;
    }

    static function delete($attributes)
    {
        $id = $attributes['id'];

        $response = static::createRequest()
            ->delete(static::getUrl("/{$id}"), static::mergeWithCredentials($attributes));

        return $response;
    }
}
