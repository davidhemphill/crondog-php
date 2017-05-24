<?php

namespace CronDog;

use Illuminate\Support\Collection;

class Schedule extends ApiResource
{
    static $endpoint = '/schedules/';

    var $attributes;

    static function get($attributes)
    {
        $response = static::createRequest()
            ->get(static::getUrl(), static::mergeWithCredentials($attributes));

        return (new Collection($response->json()))
            ->map(function ($item) {
                return static::createFromArray($item);
            });
    }

    static function find($attributes)
    {
        $id = $attributes['id'];

        $response = static::createRequest()
            ->get(static::getUrl($id), static::mergeWithCredentials($attributes));

        return static::createFromResponse($response);
    }

    static function create($attributes)
    {
        $response = static::createRequest()
            ->post(static::getUrl(), static::mergeWithCredentials($attributes));

        return static::createFromResponse($response);
    }

    static function delete($attributes)
    {
        $id = $attributes['id'];

        $response = static::createRequest()
            ->delete(static::getUrl($id), static::mergeWithCredentials($attributes));

        return static::createFromResponse($response);
    }
}
