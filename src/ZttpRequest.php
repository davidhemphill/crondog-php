<?php

namespace CronDog;

use CronDog\ZttpResponse;
use GuzzleHttp\Client;

class ZttpRequest
{
    var $headers = [];
    var $bodyFormat = 'json';

    static function new()
    {
        return new self;
    }

    function asJson()
    {
        $this->bodyFormat = 'json';
        return $this->contentType('application/json');
    }

    function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    function withHeaders($headers)
    {
        return tap($this, function ($request) use ($headers) {
            $this->headers = array_merge($this->headers, $headers);
        });
    }

    function get($url, $queryParams = [])
    {
        $response = (new Client)->get($url, [
            'query' => $queryParams,
            'headers' => $this->headers,
        ]);

        return new ZttpResponse($response);
    }

    function post($url, $params = [])
    {
        $response = (new Client)->post($url, [
            'headers' => $this->headers,
            $this->bodyFormat => $params,
        ]);

        return new ZttpResponse($response);
    }

    function delete($url, $params = [])
    {
        $response = (new Client)->delete($url, [
            'headers' => $this->headers,
            $this->bodyFormat => $params,
        ]);

        return new ZttpResponse($response);
    }
}
