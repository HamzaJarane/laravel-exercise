<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductCollection::collection(Product::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductCollection
    {
        return ProductCollection::make($product);
    }
}
