<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $optionsTitleTag = [
            'registration' => 'Регистрация',
        ];

        foreach ($optionsTitleTag as $k => $v) {
            DB::table('options')->insert([
                'tag' => $k,
                'title' => $v,
            ]);
        }
    }
}
