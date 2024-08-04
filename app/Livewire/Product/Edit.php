<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;

class Edit extends Component
{
    use WithFileUploads;

    public $product;
    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $categoria;

    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required',
        'precio' => 'required|numeric',
        'imagen' => 'nullable|image',
        'categoria' => 'required',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->nombre = $product->nombre;
        $this->descripcion = $product->descripcion;
        $this->precio = $product->precio;
        $this->categoria = $product->categoria;
    }

    public function update()
    {
        $this->validate();

        if ($this->imagen) {
            $imageName = $this->imagen->store('images', 'public');
            $this->product->imagen = $imageName;
        }

        $this->product->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'categoria' => $this->categoria,
        ]);

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.edit');
    }
}

