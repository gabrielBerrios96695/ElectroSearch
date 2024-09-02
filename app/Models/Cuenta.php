<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $primaryKey = 'idCuenta';
    protected $fillable = ['nombre', 'saldo'];
    public $timestamps = false;

    // Relación inversa con Transferencia
    public function transferenciasOrigen()
    {
        return $this->hasMany(Transferencia::class, 'idCuentaOrigen');
    }

    public function transferenciasDestino()
    {
        return $this->hasMany(Transferencia::class, 'idCuentaDestino');
    }
}
