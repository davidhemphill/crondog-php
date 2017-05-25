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
                'time' => '10:00',
            ],
            'alert' => true,
            'timezone' => 'America/Chicago'
        ]);
    }

    private function cleanHouse()
    {
        Schedule::get(['team_id' => 1])
            ->each(function ($schedule) {
                return Schedule::delete($schedule->toArray());
            });
    }

    function testCreateWorks()
    {
        $this->cleanHouse();
        $schedule = $this->createSchedule();

        $this->assertEquals(200, $schedule->getResponse()->status());
        $this->assertNotNull($schedule->id);
    }

    function testGetWorks()
    {
        $this->cleanHouse();
        $this->createSchedule();

        $schedules = Schedule::get([
            'team_id' => 1,
        ]);

        $this->assertCount(1, $schedules);
    }

    function testGettingASingleScheduleWorks()
    {
        $this->cleanHouse();
        $schedule = $this->createSchedule();

        $retrievedSchedule = Schedule::find([
            'id' => $schedule->id,
            'team_id' => 1,
        ]);

        $this->assertEquals(200, $retrievedSchedule->getResponse()->status());
        $this->assertEquals($schedule->id, $retrievedSchedule->id);
    }

    function testDeletingAScheduleWorks()
    {
        $this->cleanHouse();
        $schedule = $this->createSchedule();

        $deletedSchedule = Schedule::delete([
            'id' => $schedule->id,
            'team_id' => 1,
        ]);

        $this->assertEquals($schedule->id, $deletedSchedule->id);
        $this->assertTrue($deletedSchedule->deleted);
        $this->assertEquals(200, $deletedSchedule->getResponse()->status());
    }
}
