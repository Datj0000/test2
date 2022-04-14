<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->insert(
            array(
                [
                    'name' => 'HQ',
                    'phone' => '0391895552',
                    'email' => 'dn16092000@gmail.com',
                    'mst' => '5700133674',
                    'address' => 'Hạ Long',
                    'information' => 'MB bank 999999993599',
                ],
            )
        );
    }
}
