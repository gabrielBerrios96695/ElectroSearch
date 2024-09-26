<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $search = $request->input('search');

        // Crear consulta base para categorías
        $query = Category::query();

        // Filtrar categorías por término de búsqueda si existe
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Ordenar las categorías y obtener los resultados
        $categories = $query->orderBy($sortField, $sortDirection)->get();

        return view('livewire.categories.index', compact('categories', 'sortField', 'sortDirection', 'search'));
    }

    public function create()
    {
        return view('livewire.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'description' => 'required',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras, números y espacios.',
            'description.required' => 'La descripción es obligatoria.',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return view('livewire.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'description' => 'required',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras, números y espacios.',
            'description.required' => 'La descripción es obligatoria.',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Ocurrió un error al intentar eliminar la categoría.');
        }
    }
}
