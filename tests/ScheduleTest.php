<?php

namespace CronDog\Tests;

use CronDog\CronDog;
use CronDog\Schedule;
use PHPUnit_Framework_TestCase;
use CronDog\ScheduleNotFoundException;
use CronDog\ScheduleNotCreatedException;
use CronDog\ScheduleTeamIdNotFoundException;
use CronDog\ScheduleValidationFailedException;

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

    function testCreatingThrowsExceptionIfNoTeamIDPassed()
    {
        $this->cleanHouse();

        try {
            Schedule::create([]);
        } catch (ScheduleTeamIdNotFoundException $e) {
            $this->assertEquals(401, $e->status());
            $this->assertEquals('CronDog returned the error: You need to pass a Team ID in with every request.',
                $e->getMessage());
        }
    }

    function testCreatingThrowsExceptionIfValidationFails()
    {
        $this->cleanHouse();

        try {
            Schedule::create(['team_id' => 1]);
        } catch (ScheduleValidationFailedException $e) {
            $this->assertEquals(422, $e->status());
            $this->assertEquals(
                'The url field is required.',
                $e->getFirstError());
        }
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

        $this->assertEquals($schedule->id, $retrievedSchedule->id);
    }

    function testItShouldThrowAnExceptionIfAScheduleIsNotFound()
    {
        $this->cleanHouse();
        $schedule = $this->createSchedule();
        $deletedSchedule = Schedule::delete(['id' => $schedule->id, 'team_id' => 1]);

        try {
            Schedule::find(['id' => $schedule->id, 'team_id' => 1]);

        } catch (ScheduleNotFoundException $e) {
            $this->assertEquals(404, $e->status());
            $this->assertEquals(
                "Could not find a Schedule with that ID.",
                $e->getMessage());
        }
    }

    function testDeletingAScheduleWorks()
    {
        $this->cleanHouse();
        $schedule = $this->createSchedule();

        $deletedSchedule = Schedule::delete([
            'id' => $schedule->id,
            'team_id' => 1,
        ]);

        $this->assertEquals(200, $deletedSchedule->getResponse()->status());
        $this->assertEquals($schedule->id, $deletedSchedule->id);
        $this->assertTrue($deletedSchedule->deleted);
    }
}
