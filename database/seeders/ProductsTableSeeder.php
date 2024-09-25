<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Smartphone Galaxy S21',
                'description' => 'Samsung Galaxy S21 with 128GB storage',
                'price' => 799.99,
                'image' => 'galaxy_s21.jpg',
                'category_id' => 1, // Referencia a la categoría correspondiente
                'store_id' => 1,    // Referencia a la tienda correspondiente
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptop Dell XPS 13',
                'description' => 'Dell XPS 13 with 16GB RAM and 512GB SSD',
                'price' => 1299.99,
                'image' => 'dell_xps_13.jpg',
                'category_id' => 2, // Referencia a la categoría correspondiente
                'store_id' => 2,    // Referencia a la tienda correspondiente
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple Watch Series 6',
                'description' => 'Apple Watch with GPS and 40mm case',
                'price' => 399.99,
                'image' => 'apple_watch_s6.jpg',
                'category_id' => 3, // Referencia a la categoría correspondiente
                'store_id' => 1,    // Referencia a la tienda correspondiente
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Puedes agregar más productos aquí
        ]);
    }
}
