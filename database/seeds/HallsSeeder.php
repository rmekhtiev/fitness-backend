<?php

use App\Models\Hall;

class HallsSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        for ($i = 0; $i < 3; $i++) {
            factory(Hall::class)->create();
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
