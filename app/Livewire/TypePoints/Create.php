<?php

namespace App\Livewire\TypePoints;

use Livewire\Component;
use App\Models\TypePoint;

class Create extends Component
{
    public $name;
    public $description;
    public $status;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|boolean',
    ];

    public function save()
    {
        $this->validate();

        TypePoint::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Tipo de punto creado exitosamente.');
        return redirect()->route('type_points.index');
    }

    public function render()
    {
        return view('livewire.type-points.create');
    }
}
