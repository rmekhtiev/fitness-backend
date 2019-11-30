<?php

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;

class UserStorySeeder extends BaseSeeder
{
    /**
     * Credentials
     */
    const OWNER_CREDENTIALS = ['owner@example.com', 'secret'];

    public function runFake()
    {
        // Grab all roles for reference
        $roles = Role::all();
        $employees = Employee::all();

        // Create an admin user
        factory(App\Models\User::class)->create([
            'name'         => 'Owner',
            'email'        => static::OWNER_CREDENTIALS[0],
            'primary_role_id' => $roles->where('name', Role::ROLE_OWNER)->first()->role_id,
        ]);

        // Create regular user
        factory(App\Models\User::class)->create([
            'name'         => 'Bob',
            'email'        => 'hall-admin@example.com',
            'employee_id'  => $employees->shuffle()->pop(),
            'primary_role_id' => $roles->where('name', Role::ROLE_HALL_ADMIN)->first()->role_id,
        ]);

        // Get some random roles to assign to users
        $fakeRolesToAssignCount = 3;
        $fakeRolesToAssign = RoleTableSeeder::getRandomRoles($fakeRolesToAssignCount);

        // Assign fake roles to users
        for ($i = 0; $i < 5; ++$i) {
            $user = factory(App\Models\User::class)->create([
                'employee_id'  => $employees->shuffle()->pop(),
                'primary_role_id' => $roles->random()->role_id,
            ]);

            for ($j = 0; $j < count($fakeRolesToAssign); ++$j) {
                $user->roles()->save($fakeRolesToAssign->shift());
            }
        }
    }

    public function runProduction()
    {
        $roles = Role::all();
        
        factory(App\Models\User::class)->create([
            'name'         => 'Owner',
            'email'        => static::OWNER_CREDENTIALS[0],
            'primary_role_id' => $roles->where('name', Role::ROLE_OWNER)->first()->role_id,
        ]);
    }
}
