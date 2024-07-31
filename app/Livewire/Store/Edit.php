<?php

// app/Http/Livewire/Store/Edit.php
namespace App\Http\Livewire\Store;

use Livewire\Component;
use App\Models\Store;

class Edit extends Component
{
    public $store;
    public $name;
    public $latitude;
    public $longitude;

    public function mount($storeId)
    {
        $this->store = Store::findOrFail($storeId);
        $this->name = $this->store->name;
        $this->latitude = $this->store->latitude;
        $this->longitude = $this->store->longitude;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $this->store->update([
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        session()->flash('message', 'Tienda actualizada con éxito.');
        return redirect()->route('store.index');
    }

    public function render()
    {
        return view('livewire.store.edit');
    }
}
