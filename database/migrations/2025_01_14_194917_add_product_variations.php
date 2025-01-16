<?php

use App\Models\ProductVariation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("product_variations", function (Blueprint $table) {
            $table->id();

            $table->json("payload");
            
            $table->integer("quantity");
            $table->enum("availability", ProductVariation::AVAILABILITY)->default(ProductVariation::UNAVAILABLE);

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("product_variations");
    }
};
