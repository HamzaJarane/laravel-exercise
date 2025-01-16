<?php

namespace App\Listeners;

use App\Events\Product\ProductCreated;
use App\Events\Product\ProductDeleted;
use App\Events\Product\ProductRestored;
use App\Events\Product\ProductUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SyncThirdPartyApplication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated|ProductUpdated|ProductDeleted|ProductRestored $event): void
    {
        //
    }
}
