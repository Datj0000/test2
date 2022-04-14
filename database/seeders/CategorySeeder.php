<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            array(
                [
                    'name' => 'CPU',
                ],
                [
                    'name' => 'Ram',
                ],
                [
                    'name' => 'Tản nhiệt',
                ],
                [
                    'name' => 'Main',
                ],
                [
                    'name' => 'Card',
                ],
                [
                    'name' => 'Chuột',
                ],
                [
                    'name' => 'USB',
                ],
                [
                    'name' => 'Ổ cứng',
                ],
                [
                    'name' => 'Dây cáp',
                ],
                [
                    'name' => 'Webcam',
                ],
                [
                    'name' => 'Nguồn máy tính',
                ],
                [
                    'name' => 'Case',
                ],
                [
                    'name' => 'Loa',
                ],
                [
                    'name' => 'Lót chuột',
                ],
            )
        );
    }
}
