<?php

namespace Tests\Feature;

use App\Events\Product\ProductCreated;
use App\Events\Product\ProductDeleted;
use App\Events\Product\ProductRestored;
use App\Events\Product\ProductUpdated;
use App\Models\Product;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake([
            ProductCreated::class,
            ProductUpdated::class,
            ProductDeleted::class,
            ProductRestored::class
        ]);
    }
    
    /**
     * A basic feature test example.
     */
    public function test_model_should_dispatch_events(): void
    {
        $product = Product::factory()->create();
        Event::assertDispatched(ProductCreated::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });

        $product->update(['name' => 'Test']);
        Event::assertDispatched(ProductUpdated::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });

        $product->delete();
        Event::assertDispatched(ProductDeleted::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });

        $product->restore();
        Event::assertDispatched(ProductRestored::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });
    }
}
