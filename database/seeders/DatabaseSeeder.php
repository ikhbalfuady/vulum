<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::create([
            'name'  => 'boss',
            'username'  => 'boss',
            'email' => 'boss@mail.com',
            'password'  => 'LxC61B52HvV/ce0ZjUNSHQ==' // P@ssw0rd
        ]);

    }
}
