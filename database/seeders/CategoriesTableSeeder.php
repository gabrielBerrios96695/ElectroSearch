<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Fashion'],
            ['name' => 'Home Appliances'],
            ['name' => 'Books'],
            ['name' => 'Sports'],
            ['name' => 'Beauty'],
            ['name' => 'Automotive'],
        ];

        DB::table('categories')->insert($categories);
    }
}
