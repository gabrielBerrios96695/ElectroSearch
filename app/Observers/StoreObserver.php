<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreObserver
{
    /**
     * Handle the Store "creating" event.
     */
    public function creating(Store $store)
    {
        $store->userid = Auth::id(); 
    }


    public function updating(Store $store)
    {
        $store->userid = Auth::id(); 
    }

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store)
    {
        //
    }

    /**
     * Handle the Store "restored" event.
     */
    public function restored(Store $store)
    {
        //
    }

    /**
     * Handle the Store "force deleted" event.
     */
    public function forceDeleted(Store $store)
    {
        //
    }
}

