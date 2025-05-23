<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '一般ユーザー1',
            'email'=> 'general1@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123')
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー2',
            'email'=> 'general2@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123')
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー3',
            'email' => 'general3@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123')
        ];
        User::create($param);

    }
}
