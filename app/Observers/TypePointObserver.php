<?php

namespace App\Observers;

use App\Models\TypePoint;
use Illuminate\Support\Facades\Auth;

class TypePointObserver
{
    /**
     * Handle the TypePoint "creating" event.
     */
    public function creating(TypePoint $typePoint)
    {
        $typePoint->userid = Auth::id(); 
    }

    /**
     * Handle the TypePoint "updating" event.
     */
    public function updating(TypePoint $typePoint)
    {
        $typePoint->userid = Auth::id(); // Asigna el ID del usuario autenticado
    }

    /**
     * Handle the TypePoint "deleted" event.
     */
    public function deleted(TypePoint $typePoint)
    {
        // Lógica para cuando un TypePoint es eliminado
    }

    /**
     * Handle the TypePoint "restored" event.
     */
    public function restored(TypePoint $typePoint)
    {
        // Lógica para cuando un TypePoint es restaurado
    }

    /**
     * Handle the TypePoint "force deleted" event.
     */
    public function forceDeleted(TypePoint $typePoint)
    {
        // Lógica para cuando un TypePoint es eliminada de forma definitiva
    }
}
