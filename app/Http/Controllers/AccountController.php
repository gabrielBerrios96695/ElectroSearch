<?php

namespace App\Http\Controllers;

use App\Models\Cuenta; 
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Cuenta::all();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'saldo' => 'required|numeric|min:0',
        ]);

        Cuenta::create([
            'nombre' => $request->nombre,
            'saldo' => $request->saldo,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Cuenta creada exitosamente.');
    }

    public function edit($id)
    {
        $account = Cuenta::findOrFail($id); // Cambiado a Cuenta
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = Cuenta::findOrFail($id); // Cambiado a Cuenta

        $request->validate([
            'nombre' => 'required|string|max:255',
            'saldo' => 'required|numeric|min:0',
        ]);

        $account->update([
            'nombre' => $request->nombre,
            'saldo' => $request->saldo,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Cuenta actualizada exitosamente.');
    }

    public function credit(Request $request, $id)
    {
        $account = Cuenta::findOrFail($id); // Cambiado a Cuenta

        $request->validate([
            'cantidad' => 'required|numeric|min:0.01',
        ]);

        $account->increment('saldo', $request->cantidad);

        return redirect()->route('accounts.index')->with('success', 'Saldo abonado exitosamente.');
    }
}
