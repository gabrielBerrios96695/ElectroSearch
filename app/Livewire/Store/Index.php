<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use App\Models\Store;

class Index extends Component
{
    public $stores;
    public function mount()
    {

        $this->stores = Store::all();
    }

    public function render()
    {
        return view('livewire.store.index');
    }
}
