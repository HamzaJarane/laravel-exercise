<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductShouldSoftDeletedTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     */
    public function test_model_should_be_soft_deleted(): void
    {
        /** @var Product */
        $product = Product::factory()->create();
        $this->assertModelExists($product);

        $product->delete();
        $this->assertSoftDeleted($product);
    }
}
