<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Transferencia extends Model
{
    protected $primaryKey = 'idTransferencia';
    protected $fillable = ['idCuentaOrigen', 'idCuentaDestino', 'cantidad', 'fecha'];
    public $timestamps = false;

    protected $dates = ['fecha']; // Esto asegura que 'fecha' se maneje como un objeto Carbon

    public function cuentaOrigen()
    {
        return $this->belongsTo(Cuenta::class, 'idCuentaOrigen', 'idCuenta');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(Cuenta::class, 'idCuentaDestino', 'idCuenta');
    }
}
