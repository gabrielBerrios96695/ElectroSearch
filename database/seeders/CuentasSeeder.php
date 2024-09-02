<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuenta;

class CuentasSeeder extends Seeder
{
    public function run()
    {
        // Crear cuentas de ejemplo
        Cuenta::create([
            'nombre' => 'Cuenta de Ahorros 1',
            'saldo' => 1000.00
        ]);

        Cuenta::create([
            'nombre' => 'Cuenta de Ahorros 2',
            'saldo' => 2000.00
        ]);

        Cuenta::create([
            'nombre' => 'Cuenta Corriente 1',
            'saldo' => 500.00
        ]);

        Cuenta::create([
            'nombre' => 'Cuenta Corriente 2',
            'saldo' => 1500.00
        ]);
    }
}
