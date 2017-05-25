<?php

namespace CronDog;

use Illuminate\Support\Collection;
use GuzzleHttp\Exception\ClientException;

class Schedule extends ApiResource
{
    static $endpoint = '/schedules/';

    static function get($attributes)
    {
        $response = static::createRequest('get', $attributes);

        return (new Collection($response->json()))
            ->map(function ($item) {
                return static::createFromArray($item);
            });
    }

    static function find($attributes)
    {
        try {
            $response = static::createRequest('get', $attributes['id'], $attributes);
        } catch (ClientException $e) {
            throw new ScheduleNotFoundException('The schedule does not exist.');
        }

        return static::createFromResponse($response);
    }

    static function create($attributes)
    {
        $response = static::createRequest('post', $attributes);

        return static::createFromResponse($response);
    }

    static function delete($attributes)
    {
        $response = static::createRequest('delete', $attributes['id'], $attributes);

        return static::createFromResponse($response);
    }
}
