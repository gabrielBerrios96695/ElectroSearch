<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use App\Models\Store;

class Create extends Component
{
    public $name, $latitude, $longitude;

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Store::create([
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        return redirect()->route('store.index')->with('success', 'Tienda creada exitosamente.');
    }

    public function render()
    {
        return view('livewire.store.create');
    }
}
