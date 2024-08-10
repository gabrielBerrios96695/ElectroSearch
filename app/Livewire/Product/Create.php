<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;

class Create extends Component
{
    use WithFileUploads;

    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $categoria;

    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required',
        'precio' => 'required|numeric',
        'imagen' => 'required|image',
        'categoria' => 'required',
    ];

    public function save()
    {
        $this->validate();

        $imageName = $this->imagen->store('images', 'public');

        Product::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'imagen' => $imageName,
            'categoria' => $this->categoria,
        ]);

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.create');
    }
}
