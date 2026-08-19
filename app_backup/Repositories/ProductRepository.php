<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll()
    {
        return Product::with(['category', 'supplier'])
            ->latest()
            ->get();
    }

    public function find(int $id)
    {
        return Product::with(['category', 'supplier'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
