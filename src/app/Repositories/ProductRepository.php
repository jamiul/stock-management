<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts()
    {
        return Product::with('stock')->get();
    }

    public function getProductById($product)
    {
        return $product->load('stock');
    }

    public function deleteProduct($product)
    {
        $product->stock->delete();
        $product->delete();
    }

    public function createProduct(array $productDetails)
    {
        $product = Product::create($productDetails);
        $product->stock()->create(['quantity' => $productDetails['quantity'] ?? 0]);

        return $product;
    }

    public function updateProduct($product, array $newDetails)
    {
        $product->update($newDetails);

        $product->stock->update([
            'quantity' => $newDetails['quantity'],
        ]);

        return $product;
    }
}