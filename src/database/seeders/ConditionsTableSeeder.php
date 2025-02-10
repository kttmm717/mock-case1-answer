<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い'
        ];
        foreach($params as $param) {
            DB::table('conditions')->insert([
                'condition' => $param,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
