<?php

use CronDog\CronDog;
use CronDog\Schedule;

class ScheduleTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        parent::setUp();

        CronDog::setApiKey(getenv('CRONDOG_KEY'));
    }

    private function createSchedule()
    {
        return Schedule::create([
            'team_id' => 1,
            'url' => 'http://davidhemphill.dev',
            'method' => 'get',
            'type' => 'monthly',
            'monthly' => [
            'day' => 14,
            ],
            'alert' => true,
            'timezone' => 'America/Chicago'
        ]);
    }

    /**
     * @vcr create_schedule_test
     */
    function testCreateWorks()
    {
        $response = Schedule::create([
            'team_id' => 1,
            'url' => 'http://davidhemphill.dev',
            'method' => 'get',
            'type' => 'monthly',
            'monthly' => [
                'day' => 14,
            ],
            'alert' => true,
            'timezone' => 'America/Chicago'
        ]);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @vcr get_schedules_test
     */
    function testGetWorks()
    {
        $response = $this->createSchedule();

        $schedules = Schedule::get([
            'team_id' => 1,
        ]);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @vcr get_schedule_test
     */
    function testGettingASingleScheduleWorks()
    {
        $response = $this->createSchedule();
        $id = $response->json()['id'];

        $retrievedSchedule = Schedule::find([
            'id' => $id,
            'team_id' => 1,
        ]);

        $this->assertEquals(200, $retrievedSchedule->status());
    }

    /**
     * @vcr delete_schedule_test
     */
    function testDeletingAScheduleWorks()
    {
        $response = $this->createSchedule();
        $id = $response->json()['id'];

        $retrievedSchedule = Schedule::delete([
            'id' => $id,
            '_method' => 'delete',
            'team_id' => 1,
        ]);

        $this->assertEquals(200, $retrievedSchedule->status());
    }
}
