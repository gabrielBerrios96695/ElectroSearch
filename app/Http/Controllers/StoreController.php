<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $stores = Store::orderBy($sortField, $sortDirection)->get();

        return view('livewire/store.index', compact('stores', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire/store.create');
    }

    public function edit(Store $store)
    {
        return view('livewire/store.edit', compact('store'));
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

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('store.index')->with('success', 'Tienda eliminada correctamente');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Store::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda creada exitosamente.');
    }
}
