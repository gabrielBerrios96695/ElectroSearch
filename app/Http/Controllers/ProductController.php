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
            'nombre' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'descripcion' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'precio' => 'required|numeric|min:0',
            'imagen' => 'required|image',
            'categoria' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'store_id' => 'required|exists:stores,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, números y espacios.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números y espacios.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.regex' => 'La categoría solo puede contener letras, números y espacios.',
            'store_id.required' => 'El ID de la tienda es obligatorio.',
            'store_id.exists' => 'El ID de la tienda debe existir en la base de datos.',
        ]);

        $productCount = Product::where('store_id', $request->store_id)->count() + 1;

        if ($request->hasFile('imagen')) {
            $extension = $request->imagen->extension();
            $imageName = 't' . $request->store_id . '-p' . $productCount . '.' . $extension;
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

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        return view('livewire.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nombre' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'descripcion' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image',
            'categoria' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'store_id' => 'required|exists:stores,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, números y espacios.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números y espacios.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.',
            'imagen.nullable' => 'La imagen es opcional.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.regex' => 'La categoría solo puede contener letras, números y espacios.',
            'store_id.required' => 'El ID de la tienda es obligatorio.',
            'store_id.exists' => 'El ID de la tienda debe existir en la base de datos.',
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::delete('public/images/' . $product->imagen);
            }

            $extension = $request->imagen->extension();
            $imageName = 't' . $request->store_id . '-p' . $product->id . '.' . $extension;
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

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        
            try {
                if ($product->imagen) {
                    Storage::delete('public/images/' . $product->imagen);
                }
        
                $product->delete();
                return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
            } catch (\Exception $e) {
                return redirect()->route('products.index')->with('error', 'Ocurrió un error al intentar eliminar el producto.');
            }
        
    }
}
