<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('stock')->get();
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->stock()->create(['quantity' => 0]);

        return response()->json($product);
    }

    public function show(Product $product)
    {
        return $product->load('stock');
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        $product->stock->update([
            'quantity' => $request['quantity'],
        ]);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
