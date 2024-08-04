<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('livewire.products.index', compact('products'));
    }

    public function create()
    {
        return view('livewire.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'imagen' => 'required|image',
            'categoria' => 'required',
        ]);

        $imageName = $request->file('imagen')->store('images', 'public');

        Product::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $imageName,
            'categoria' => $request->categoria,
        ]);

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('livewire.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image',
            'categoria' => 'required',
        ]);

        if ($request->hasFile('imagen')) {
            $imageName = $request->file('imagen')->store('images', 'public');
            $product->imagen = $imageName;
        }

        $product->update($request->only(['nombre', 'descripcion', 'precio', 'categoria']));

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }
}
