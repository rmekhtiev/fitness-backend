<?php

use App\Models\Group;
use App\Models\Schedule;
use App\Models\Hall;

class GroupScheduleSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake()
    {
        /** @var Group[] $groups */
        $groups = Group::all();

        foreach ($groups as $group) {

            for ($i = 0; $i < rand(1, 2); $i++) {
                $group->schedules()->create(factory(Schedule::class)->raw());
            }
        }
    }

    /**
     * Run seeds to be ran only on production environments
     *
     * @return mixed
     */
    public function runProduction()
    {

    }

    /**
     * Run seeds to be ran on every environment (including production)
     *
     * @return mixed
     */
    public function runAlways()
    {

    }
}
