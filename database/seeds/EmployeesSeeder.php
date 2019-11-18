<?php

use App\Models\Employee;
use App\Models\Hall;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class EmployeesSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake()
    {
        $halls = Hall::all();

        for ($i = 0; $i <= 20; $i++) {
            factory(Employee::class)->create([
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
