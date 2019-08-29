<?php

use App\Models\Role;

class RoleTableSeeder extends BaseSeeder
{
    public function runAlways()
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'Administrator Users',
        ]);

        Role::firstOrCreate([
            'name' => 'hall_admin',
            'description' => 'Hall Administrators',
        ]);

        Role::firstOrCreate([
            'name' => 'concierge',
            'description' => 'Concierge',
        ]);
    }

    /**
     * Get a collection of random roles
     * Remove duplicates to prevent SQL errors, also prevent infinite loop in case of not enough roles
     *
     * @param $count int How many roles to get
     * @return Illuminate\Support\Collection
     */
    public static function getRandomRoles($count)
    {
        $roles = Role::all();

        $fakeRoles = [];
        $i = 0;

        do {
            ++$i;
            $fakeRoles[] = $roles->whereNotIn('name', ['admin'])->random();
            $fakeRoles = array_unique($fakeRoles);
        } while (count($fakeRoles) < $count && $i < 50); // Iteration limit

        return collect($fakeRoles);
    }
}
