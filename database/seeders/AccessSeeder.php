<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accessesTitleTag = [
            'personal' => 'Личный',
            'all' => 'Для всех',
            'authorized' => 'Авторизованным',
            'specificUsers' => 'Определенным пользователям',
        ];

        foreach ($accessesTitleTag as $k => $v) {
            DB::table('accesses')->insert([
                'tag' => $k,
                'title' => $v,
            ]);
        }

    }
}
