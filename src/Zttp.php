<?php

namespace CronDog;

use CronDog\ZttpRequest;

class Zttp
{
    static function __callStatic($method, $args)
    {
        return ZttpRequest::new()->{$method}(...$args);
    }
}
