<?php

namespace CronDog;

use Illuminate\Support\Collection;
use CronDog\ScheduleNotFoundException;
use CronDog\ScheduleNotCreatedException;
use CronDog\ScheduleTeamIdNotFoundException;
use CronDog\ScheduleValidationFailedException;

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
        $response = static::createRequest('get', $attributes['id'], $attributes);

        if (! $response->isSuccess()) {
            throw new ScheduleNotFoundException(
                "Could not find a Schedule with that ID.",
                $response->status(),
                $response->body(),
                $response->json()
            );
        }

        return static::createFromResponse($response);
    }

    static function create($attributes)
    {
        $response = static::createRequest('post', $attributes);

        if (! $response->isSuccess()) {
            if ($response->status() == 401) {
                throw new ScheduleTeamIdNotFoundException(
                    "CronDog returned the error: {$response->message}",
                    $response->status(),
                    $response->body(),
                    $response->json()
                );
            }

            if ($response->status() == 422) {
                throw new ScheduleValidationFailedException(
                    "There was an error validating this request",
                    $response->status(),
                    $response->body(),
                    $response->json()
                );
            }
        }

        return static::createFromResponse($response);
    }

    static function delete($attributes)
    {
        $response = static::createRequest('delete', $attributes['id'], $attributes);

        return static::createFromResponse($response);
    }
}
