<?php

use App\Models\Employee;
use App\Models\Issue;
use App\Models\Role;
use App\Models\User;

class IssuesSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {
        $employees = Employee::all()->random(10);
        $user = User::query()->where('name', 'Owner')->first();
        for ($i = 0; $i < 10; $i++) {
            $employee=$employees->shift();
            factory(Issue::class)->create([
                'hall_id' => $employee->hall_id,
                'employee_id' => $employee->employee_id,
                'user_id' => $user->user_id,
                'status' => 'pending',
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
