<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->id('idTransferencia');
            $table->unsignedBigInteger('idCuentaOrigen');
            $table->unsignedBigInteger('idCuentaDestino');
            $table->decimal('cantidad', 10, 2);
            $table->timestamp('fecha');
    
            $table->foreign('idCuentaOrigen')->references('idCuenta')->on('cuentas')->onDelete('cascade');
            $table->foreign('idCuentaDestino')->references('idCuenta')->on('cuentas')->onDelete('cascade');
       
        });
    }
    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};
