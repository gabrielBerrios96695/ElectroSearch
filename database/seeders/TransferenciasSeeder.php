<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transferencia;
use Carbon\Carbon;

class TransferenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transferencia::create([
            'idCuentaOrigen' => 1,
            'idCuentaDestino' => 2,
            'cantidad' => 100.00,
            'fecha' => Carbon::now()->subDays(10)
        ]);

        Transferencia::create([
            'idCuentaOrigen' => 2,
            'idCuentaDestino' => 3,
            'cantidad' => 200.00,
            'fecha' => Carbon::now()->subDays(5)
        ]);

        Transferencia::create([
            'idCuentaOrigen' => 3,
            'idCuentaDestino' => 4,
            'cantidad' => 50.00,
            'fecha' => Carbon::now()->subDays(2)
        ]);

        Transferencia::create([
            'idCuentaOrigen' => 4,
            'idCuentaDestino' => 1,
            'cantidad' => 300.00,
            'fecha' => Carbon::now()
        ]);
    }
}
