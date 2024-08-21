<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypePointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_points')->insert([
            [
                'name' => 'Centro de Reciclaje',
                'description' => 'Un lugar donde se recolectan y procesan materiales reciclables.',
                'status' => 0,
                'userid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Punto de Recolección de Residuos',
                'description' => 'Área designada para la recolección de residuos para su eliminación.',
                'status' => 1,
                'userid' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Centro de Compra de Materiales Reciclables',
                'description' => 'Instalación que compra materiales reciclables al público.',
                'status' => 1,
                'userid' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Punto de Compostaje',
                'description' => 'Lugar donde se procesan desechos orgánicos para crear compost.',
                'status' => 1,
                'userid' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Punto de Reciclaje de Electrónicos',
                'description' => 'Área destinada a la recolección y procesamiento de dispositivos electrónicos para reciclaje.',
                'status' => 0,
                'userid' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
