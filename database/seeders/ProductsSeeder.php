<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Producto 1',
                'description' => 'Descripción del producto 1.',
                'image' => 'products/p1.png', 
                'quantity' => 15,
                'price' => 120.00,
                'category_id' => 1,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 2',
                'description' => 'Descripción del producto 2.',
                'image' => 'path/to/image2.jpg',
                'quantity' => 30,
                'price' => 80.00,
                'category_id' => 2,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 3',
                'description' => 'Descripción del producto 3.',
                'image' => 'path/to/image3.jpg',
                'quantity' => 25,
                'price' => 55.00,
                'category_id' => 1,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 4',
                'description' => 'Descripción del producto 4.',
                'image' => 'path/to/image4.jpg',
                'quantity' => 10,
                'price' => 99.00,
                'category_id' => 3,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 5',
                'description' => 'Descripción del producto 5.',
                'image' => 'path/to/image5.jpg',
                'quantity' => 50,
                'price' => 40.00,
                'category_id' => 2,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 6',
                'description' => 'Descripción del producto 6.',
                'image' => 'path/to/image6.jpg',
                'quantity' => 20,
                'price' => 70.00,
                'category_id' => 4,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 7',
                'description' => 'Descripción del producto 7.',
                'image' => 'path/to/image7.jpg',
                'quantity' => 45,
                'price' => 65.00,
                'category_id' => 1,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 8',
                'description' => 'Descripción del producto 8.',
                'image' => 'path/to/image8.jpg',
                'quantity' => 12,
                'price' => 110.00,
                'category_id' => 3,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 9',
                'description' => 'Descripción del producto 9.',
                'image' => 'path/to/image9.jpg',
                'quantity' => 8,
                'price' => 150.00,
                'category_id' => 2,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 10',
                'description' => 'Descripción del producto 10.',
                'image' => 'path/to/image10.jpg',
                'quantity' => 5,
                'price' => 200.00,
                'category_id' => 4,
                'status' => 1,
                'userId' => 1,
                'store_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
