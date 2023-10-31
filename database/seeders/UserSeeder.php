<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'name' => 'Админ',
                'surname' => 'Админ',
                'email' => 'test@test.ru',
                'password' => bcrypt('!JJ2vd'),
                'activation' => 1,
                'role_id' => 3,
                'created_at' => NOW(),
                'updated_at' => NOW(),
        ]);
    }
}
