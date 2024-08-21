<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('collection_points')->insert([
            [
                'name' => 'Punto de Recolección Central',
                'latitude' => 19.432608,
                'longitude' => -99.133209,
                'status' => 1,
                'userid' => 1,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'type_point_id' => 1,
            ],
            [
                'name' => 'Punto Ecológico Norte',
                'latitude' => 19.452848,
                'longitude' => -99.102197,
                'status' => 1,
                'userid' => 1,
                'opening_time' => '09:00:00',
                'closing_time' => '19:00:00',
                'type_point_id' => 2,
            ],
            [
                'name' => 'Punto Verde Este',
                'latitude' => 19.400201,
                'longitude' => -99.128311,
                'status' => 1,
                'userid' => 1,
                'opening_time' => '07:00:00',
                'closing_time' => '17:00:00',
                'type_point_id' => 3,
            ],
            [
                'name' => 'Punto de Recolección Sur',
                'latitude' => 19.373333,
                'longitude' => -99.162792,
                'status' => 1,
                'userid' => 1,
                'opening_time' => '08:30:00',
                'closing_time' => '18:30:00',
                'type_point_id' => 4,
            ],
            [
                'name' => 'Punto Reciclaje Oeste',
                'latitude' => 19.409996,
                'longitude' => -99.199716,
                'status' => 1,
                'userid' => 1,
                'opening_time' => '10:00:00',
                'closing_time' => '20:00:00',
                'type_point_id' => 5,
            ],
        ]);

       
    }
}
