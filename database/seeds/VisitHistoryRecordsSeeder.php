<?php

use App\Models\Client;
use App\Models\Hall;
use App\Models\VisitHistoryRecord;

class VisitHistoryRecordsSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        $halls = Hall::all();
        $count = Client::count();
        $clients = Client::inRandomOrder()->limit($count*0.8)->get();
        foreach ($clients as $client){
            for ($i=0;$i<rand(1,4);$i++){
                factory(VisitHistoryRecord::class)->create([
                    'client_id' => $client->client_id,
                    'hall_id' => $halls->random()->hall_id,
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
