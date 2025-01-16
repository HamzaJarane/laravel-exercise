<?php

namespace App\Listeners\Notifications;

use App\Events\Product\ProductCreated;
use App\Events\Product\ProductDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWareHouse
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
    public function handle(ProductCreated|ProductDeleted $event): void
    {
        
    }
}
