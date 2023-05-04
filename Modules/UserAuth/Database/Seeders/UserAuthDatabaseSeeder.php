<?php

namespace Modules\UserAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\UserAuth\Entities\Ability;
use Modules\UserAuth\Entities\Role;

class UserAuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultRoles = ['user', 'admin', 'student', 'coworker'];

        foreach ($defaultRoles as $role) {
            Role::create(['name' => $role]);
        }

        $defaultAbilities = [
            'can_create_book',
            'can_create_library',
        ];

        foreach ($defaultAbilities as $ability) {
            Ability::create(['name' => $ability]);
        }
        // $this->call("OthersTableSeeder");
    }
}
