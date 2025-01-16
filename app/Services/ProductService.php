<?php

namespace App\Services;

use App\Interfaces\BaseService;
use App\Models\Product;

class ProductService implements BaseService
{
    protected Product $product;

    public function __construct()
    {
        $this->product = app(Product::class);
    }

    public function getModel(): Product
    {
        return $this->product;
    }

    public function all()
    {
        return $this->product::all();
    }

    public function create(array $data)
    {
        return $this->product::create($data);
    }

    public function update(array $data, $id)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->product::findOrFail($id);
        $product->delete();
    }

    public function find($id)
    {
        return $this->product::findOrFail($id);
    }
}