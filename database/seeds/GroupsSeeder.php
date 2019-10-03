<?php

use App\Models\Group;
use App\Models\Hall;

class GroupsSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake()
    {
        $halls = Hall::all();
        for ($i = 0; $i < 5; $i++) {
            factory(Group::class)->create([
                'hall_id' => $halls->random()->hall_id,
            ]);
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
