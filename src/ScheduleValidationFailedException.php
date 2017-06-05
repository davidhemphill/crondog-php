<?php

namespace CronDog;

use CronDog\BaseException;

class ScheduleValidationFailedException extends BaseException {
    function getFirstError()
    {
        return collect($this->json)->flatten()->first();
    }
}