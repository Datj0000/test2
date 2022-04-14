<?php

namespace Database\Seeders;

use App\Models\User;
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
        DB::table('users')->insert(
            array(
                [
                    'name' => '',
                    'email' => 'ceo@funnydev.vn',
                    'phone' => '',
                    'password' => bcrypt(123456),
                    'role' => '0',
                ],
            )
        );
    }
}
