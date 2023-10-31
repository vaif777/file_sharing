<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesTitleTag = [
            'user' => 'Пользователь',
            'employee' => 'Cотрудник',
            'administrator' => 'Администратор',
        ];

        foreach ($rolesTitleTag as $k => $v) {
            DB::table('roles')->insert([
                'tag' => $k,
                'title' => $v,
            ]);
        }
    }
}
