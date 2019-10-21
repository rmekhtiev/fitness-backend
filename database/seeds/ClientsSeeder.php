<?php

use App\Models\Client;
use App\Models\Hall;

class ClientsSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        $halls = Hall::all();

        for ($i = 0; $i < 100; $i++) {
            factory(Client::class)->create([
                'primary_hall_id' => $halls->random()->hall_id,
                'created_at' => $this->faker->dateTimeBetween('-1 year')
            ]);
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
