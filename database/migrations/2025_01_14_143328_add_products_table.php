<?php

use App\Models\Product;
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
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("sku")->unique();
            $table->enum("status", array_values(Product::STATUS))->default(Product::HIDDEN);
            $table->decimal("price", 7, 2)->default(0);
            $table->string("currency", 20)->nullable();
            $table->string("image")->nullable();
            $table->integer("quantity")->default(0);
            $table->string("delete_reason")->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
