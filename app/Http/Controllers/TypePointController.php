<?php

namespace App\Http\Controllers;

use App\Models\TypePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypePointController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $validSortFields = ['id', 'name']; // Añade otros campos si es necesario
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'id';
        }

        $typePoints = TypePoint::orderBy($sortField, $sortDirection)->paginate(10);

        return view('livewire/type-points.index', compact('typePoints', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire/type-points.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:type_points',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'El nombre ya está en uso.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        TypePoint::create([
            'name' => $request->name,
            'description' => $request->description,
            'userid' => Auth::user()->id
        ]);

        return redirect()->route('type_points.index')->with('success', 'Tipo de punto creado exitosamente.');
    }

    public function edit(TypePoint $typePoint)
    {
        return view('livewire/type-points.edit', compact('typePoint'));
    }

    public function update(Request $request, TypePoint $typePoint)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:type_points,name,' . $typePoint->id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'El nombre ya está en uso.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        $typePoint->update([
            'name' => $request->name,
            'description' => $request->description,
            'userid' => Auth::user()->id
        ]);

        return redirect()->route('type_points.index')->with('success', 'Tipo de punto actualizado correctamente.');
    }

    public function destroy(TypePoint $typePoint)
    {
        $typePoint->delete();

        return redirect()->route('livewire/type-points.index')->with('success', 'Tipo de punto eliminado correctamente.');
    }

    public function toggleStatus($id)
    {
        $typePoint = TypePoint::findOrFail($id);
        $typePoint->status = !$typePoint->status;
        $typePoint->userid = Auth::user()->id;
        $typePoint->save();

        return redirect()->route('type_points.index')->with('success', 'Estado del tipo de punto actualizado.');
    }
}
