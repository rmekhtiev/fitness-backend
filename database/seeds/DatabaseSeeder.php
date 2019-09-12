<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UserStorySeeder::class);
        $this->call(HallsSeeder::class);
        $this->call(EmployeesSeeder::class);
        $this->call(ClientsSeeder::class);
        $this->call(LockersSeeder::class);
        $this->call(IssuesSeeder::class);
    }
}
