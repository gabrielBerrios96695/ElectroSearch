<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
{
    $user->userid = Auth::check() ? Auth::id() : 0; 
}

public function updating(User $user): void
{
    $user->userid = Auth::check() ? Auth::id() : 1; 
}


    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
