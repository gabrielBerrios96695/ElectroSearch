<?php
namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Transferencia;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = Transferencia::with(['cuentaOrigen', 'cuentaDestino'])->get();
        return view('transfers.index', compact('transfers'));
    }
    public function create()
    {
        // Obtener todas las cuentas para la vista
        $accounts = Cuenta::all();
        return view('transfers.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuenta_origen' => 'required|exists:cuentas,idCuenta',
            'cuenta_destino' => 'required|exists:cuentas,idCuenta',
            'cantidad' => 'required|numeric|min:0',
        ]);

        // Inicia una transacción para asegurar que ambas operaciones se realicen correctamente
        \DB::transaction(function () use ($request) {
            $cuentaOrigen = Cuenta::findOrFail($request->cuenta_origen);
            $cuentaDestino = Cuenta::findOrFail($request->cuenta_destino);

            if ($cuentaOrigen->saldo < $request->cantidad) {
                return back()->withErrors(['cantidad' => 'Saldo insuficiente en la cuenta de origen.']);
            }

            // Debitar la cuenta de origen
            $cuentaOrigen->saldo -= $request->cantidad;
            $cuentaOrigen->save();

            // Abonar la cuenta de destino
            $cuentaDestino->saldo += $request->cantidad;
            $cuentaDestino->save();

            // Registrar la transferencia
            Transferencia::create([
                'idCuentaOrigen' => $cuentaOrigen->idCuenta,
                'idCuentaDestino' => $cuentaDestino->idCuenta,
                'cantidad' => $request->cantidad,
                'fecha' => now(),
            ]);
        });

        return redirect()->route('transfers.index')->with('success', 'Transferencia realizada correctamente.');
    }
}
