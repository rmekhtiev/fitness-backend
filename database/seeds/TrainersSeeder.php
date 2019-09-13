<?php

use App\Models\Employee;
use App\Models\Trainer;

class TrainersSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake()
    {
        $associatedEmployees = Employee::all()->random(10);
        for ($i = 0; $i < 10; $i++) {
            $employee=$associatedEmployees->shift();
            factory(Trainer::class)->create([
                'first_name' => $employee->first_name,
                'last_name' =>$employee->last_name,
                'associated_employee_id' => $employee->employee_id,
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
