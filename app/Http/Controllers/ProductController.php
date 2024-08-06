<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'store_id' => 'required|exists:stores,id',
        ]);

        $productCount = Product::where('store_id', $request->store_id)->count() + 1;

        if ($request->hasFile('imagen')) {
            $extension = $request->imagen->extension();
            $imageName = 'tienda' . $request->store_id . '-producto' . $productCount . '.' . $extension;
            $path = $request->imagen->storeAs('public/images', $imageName);
        }

        Product::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $imageName ?? null,
            'categoria' => $request->categoria,
            'store_id' => $request->store_id,
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
            'store_id' => 'required|exists:stores,id',
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::delete('public/images/' . $product->imagen);
            }

            $extension = $request->imagen->extension();
            $imageName = 'tienda' . $request->store_id . '-producto' . $product->id . '.' . $extension;
            $path = $request->imagen->storeAs('public/images', $imageName);
            $product->imagen = $imageName;
        }

        $product->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $product->imagen,
            'categoria' => $request->categoria,
            'store_id' => $request->store_id,
        ]);

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        if ($product->imagen) {
            Storage::delete('public/images/' . $product->imagen);
        }

        $product->delete();

        return redirect()->route('products.index');
    }
}
