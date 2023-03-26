<?php

namespace Modules\UserAuth\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\UserAuth\Entities\User;

class UserAuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        User::factory()->create([
            'email' => 'test@gmail.com',
            'lastname' => 'Сауырбай',
            'firstname' => 'Иманғали',
            'patronymic' => 'Жеңісбекұлы'
        ]);
        // $this->call("OthersTableSeeder");
    }
}
