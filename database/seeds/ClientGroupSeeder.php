<?php

use App\Models\Client;
use App\Models\Group;

class ClientGroupSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        $groups = Group::all();
        $clients = Client::all();
        foreach ($groups as $group){
            foreach ($this->faker->randomElements($clients, $group->max_members) as $client){
                $group->clients()->attach($client);
            }
        }
    }
    /**
     * Run seeds to be ran only on production environments
     *
     * @return mixed
     */
    public function runProduction() {

    }

    /**
     * Run seeds to be ran on every environment (including production)
     *
     * @return mixed
     */
    public function runAlways() {

    }
}
