<?php

use App\Models\BarItem;
use App\Models\Hall;

class BarItemsSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        $halls = Hall::all();

        foreach ($halls as $hall) {
            for ($i = 1; $i <= $this->faker->numberBetween(0, 10); $i++) {
                factory(BarItem::class)->create([
                    'hall_id' => $hall->hall_id,
                ]);
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
