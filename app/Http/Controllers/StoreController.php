<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        $query = Store::query(); // Inicializamos la consulta
    
        // Si hay una búsqueda, agregamos un filtro por el nombre de la tienda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%"); // Filtrar por nombre de la tienda
        }
    
        // Filtrar por administrador según el rol
        if ($user->role == 1 && $user->id == 1) {
            $stores = $query->get();
        } elseif ($user->role == 1) {
            $stores = $query->where('admin_id', $user->id)->get();
        } elseif ($user->role == 3) {
            $stores = $query->get(['name', 'latitude', 'longitude', 'opening_time', 'closing_time']);
            return view('livewire.store.map', compact('stores'));
        } else {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    
        return view('livewire.store.index', compact('stores'));
    }
    public function create()
    {
        return view('livewire.store.create'); // O la vista correspondiente para crear una tienda
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
        ]);
    
        // Crear una nueva tienda
        $store = new Store();
        $store->name = $validatedData['name'];
        $store->latitude = $validatedData['latitude'];
        $store->longitude = $validatedData['longitude'];
        $store->opening_time = $validatedData['opening_time'];
        $store->closing_time = $validatedData['closing_time'];
        $store->admin_id = auth()->user()->id;  // Usar el ID del usuario autenticado
    
        // Guardar la tienda en la base de datos
        $store->save();
    
        // Redirigir a la lista de tiendas con un mensaje de éxito
        return redirect()->route('store.index')->with('success', 'Tienda registrada correctamente.');
    }
    public function edit($id)
{
    // Obtener la tienda por su ID
    $store = Store::findOrFail($id);

    // Devolver la vista de edición con los datos de la tienda
    return view('livewire.store.edit', compact('store'));
}
public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $store->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda actualizada correctamente');
    }
    public function toggleStatus($id)
{
    // Obtener la tienda por su ID
    $store = Store::findOrFail($id);

    // Cambiar el estado de la tienda (1 -> 0 o 0 -> 1)
    $store->status = !$store->status;

    // Guardar el nuevo estado en la base de datos
    $store->save();

    // Redirigir a la lista de tiendas con un mensaje de éxito
    return redirect()->route('store.index')->with('success', 'Estado de la tienda actualizado.');
}


}
