<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->truncate();

        // Crear tiendas de ejemplo
        DB::table('stores')->insert([
            [
                'name' => 'Tienda Principal',
                'latitude' => -17.3833,
                'longitude' => -66.1833,
                'status' => 1,
                'userid' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sucursal Centro',
                'latitude' => -17.3880,
                'longitude' => -66.1870,
                'status' => 1,
                'userid' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sucursal Norte',
                'latitude' => -17.3790,
                'longitude' => -66.1800,
                'status' => 1,
                'userid' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tienda Sur',
                'latitude' => -17.3730,
                'longitude' => -66.1900,
                'status' => 1,
                'userid' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tienda Este',
                'latitude' => -17.3870,
                'longitude' => -66.1750,
                'status' => 1,
                'userid' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
