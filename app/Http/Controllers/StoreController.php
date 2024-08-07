<?php
namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (Auth::user()->isAdmin()) {
            $stores = Store::orderBy($sortField, $sortDirection)->get();
        } else {
            $stores = Store::where('status', 1)->orderBy($sortField, $sortDirection)->get();
        }

        // Obtener los nombres de los usuarios
        foreach ($stores as $store) {
            $store->created_by = User::find($store->userid)->name ?? 'N/A';
        }

        return view('livewire.store.index', compact('stores', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire.store.create');
    }

    public function edit(Store $store)
    {
        return view('livewire.store.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            
            'name.regex' => 'El nombre no puede tener caracteres',
            
        ]);

        $store->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda actualizada correctamente.');
    }

    public function destroy(Store $store)
    {
        // Agregar lógica para eliminar la tienda
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'name.required' => 'El nombre de la tienda es obligatorio.',
            'name.string' => 'El nombre de la tienda debe ser una cadena de texto.',
            'name.max' => 'El nombre de la tienda no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre de la tienda solo puede contener letras y espacios.',
            'latitude.required' => 'La latitud es obligatoria.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'longitude.required' => 'La longitud es obligatoria.',
            'longitude.numeric' => 'La longitud debe ser un número.',
        ]);

        Store::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda creada exitosamente.');
    }

    public function toggleStatus(Store $store)
    {
        $store->status = !$store->status;
        $store->save();

        return redirect()->route('store.index')->with('success', 'Estado de la tienda actualizado correctamente.');
    }
}
