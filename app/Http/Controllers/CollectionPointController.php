<?php

namespace App\Http\Controllers;

use App\Models\CollectionPoint;
use App\Models\TypePoint;
use Illuminate\Http\Request;

class CollectionPointController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $validSortFields = ['id', 'name', 'latitude', 'longitude', 'opening_time', 'closing_time', 'type_point_id','userid']; // Añade otros campos si es necesario
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'id';
        }

        $collectionPoints = CollectionPoint::orderBy($sortField, $sortDirection)->paginate(10);

        return view('livewire/collection-points.index', compact('collectionPoints', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $typePoints = TypePoint::all(); // Para llenar el campo de selección en el formulario
        return view('livewire/collection-points.create', compact('typePoints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'type_point_id' => 'required|exists:type_points,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'latitude.required' => 'La latitud es obligatoria.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'longitude.required' => 'La longitud es obligatoria.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'opening_time.required' => 'La hora de apertura es obligatoria.',
            'opening_time.date_format' => 'La hora de apertura debe estar en formato HH:mm.',
            'closing_time.required' => 'La hora de cierre es obligatoria.',
            'closing_time.date_format' => 'La hora de cierre debe estar en formato HH:mm.',
            'type_point_id.required' => 'El tipo de punto es obligatorio.',
            'type_point_id.exists' => 'El tipo de punto seleccionado no es válido.',
        ]);

        CollectionPoint::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'type_point_id' => $request->type_point_id,
        ]);

        return redirect()->route('collection_points.index')->with('success', 'Punto de recolección creado exitosamente.');
    }

    public function edit(CollectionPoint $collectionPoint)
    {
        $typePoints = TypePoint::all(); // Para llenar el campo de selección en el formulario
        return view('livewire/collection-points.edit', compact('collectionPoint', 'typePoints'));
    }

    public function update(Request $request, CollectionPoint $collectionPoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'type_point_id' => 'required|exists:type_points,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'latitude.required' => 'La latitud es obligatoria.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'longitude.required' => 'La longitud es obligatoria.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'opening_time.required' => 'La hora de apertura es obligatoria.',
            'opening_time.date_format' => 'La hora de apertura debe estar en formato HH:mm.',
            'closing_time.required' => 'La hora de cierre es obligatoria.',
            'closing_time.date_format' => 'La hora de cierre debe estar en formato HH:mm.',
            'type_point_id.required' => 'El tipo de punto es obligatorio.',
            'type_point_id.exists' => 'El tipo de punto seleccionado no es válido.',
        ]);

        $collectionPoint->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'type_point_id' => $request->type_point_id,
        ]);

        return redirect()->route('collection_points.index')->with('success', 'Punto de recolección actualizado correctamente.');
    }

    public function destroy(CollectionPoint $collectionPoint)
    {
        $collectionPoint->delete();

        return redirect()->route('collection_points.index')->with('success', 'Punto de recolección eliminado correctamente.');
    }

    public function toggleStatus($id)
    {
        $collectionPoint = CollectionPoint::findOrFail($id);
        $collectionPoint->status = !$collectionPoint->status;
        $collectionPoint->save();

        return redirect()->route('collection_points.index')->with('success', 'Estado del punto de recolección actualizado.');
    }
}
