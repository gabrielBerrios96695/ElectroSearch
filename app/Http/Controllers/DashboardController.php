<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Tiendas
        $storeCount = Store::count();
        $storeCount1 = Store::where('status', 0)->count();

        // Usuarios
        $sellersCount = User::where('role', '2')->count();
        $clientsCount = User::where('role', '3')->count();

        return view('dashboard', compact('storeCount', 'storeCount1', 'sellersCount', 'clientsCount', 'user'));
    }


public function updatePassword(Request $request)
{
    $user = Auth::user();

    // Validar que la contraseña actual es correcta
    $request->validate([
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        throw ValidationException::withMessages([
            'current_password' => ['La contraseña actual es incorrecta.'],
        ]);
    }

    if (Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => ['La nueva contraseña no puede ser igual a la contraseña actual.'],
        ]);
    }

    // Actualizar la contraseña
    $user->password = Hash::make($request->password);
    $user->passwordUpdate = false; // Marca como actualizado
    $user->save();

    return redirect()->route('dashboard')->with('status', 'Contraseña actualizada con éxito, muchas gracias');
}


}
