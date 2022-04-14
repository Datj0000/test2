<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert(
            array(
                [
                    'name' => 'Lexar',
                ],
                [
                    'name' => 'Asus',
                ],
                [
                    'name' => 'Kingston',
                ],
                [
                    'name' => 'Corsair',
                ],
                [
                    'name' => 'Pisen',
                ],
                [
                    'name' => 'MSI',
                ],
                [
                    'name' => 'Xiaomi',
                ],
                [
                    'name' => 'HP',
                ],
                [
                    'name' => 'Tomato',
                ],
                [
                    'name' => 'Logitech',
                ],
                [
                    'name' => 'LG',
                ],
                [
                    'name' => 'BenQ',
                ],
                [
                    'name' => 'Lenovo',
                ],
                [
                    'name' => 'AKKO',
                ],
            )
        );
    }
}
