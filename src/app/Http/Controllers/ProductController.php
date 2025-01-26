<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    private function getCachedData(string $cacheKey, callable $callback, int $ttl = 3600)
    {
        $cachedData = Redis::get($cacheKey);

        if ($cachedData) {
            return json_decode($cachedData);
        }

        $data = $callback();
        Redis::set($cacheKey, json_encode($data));
        Redis::expire($cacheKey, $ttl);

        return $data;
    }

    public function index()
    {
        $cacheKey = "product:all";

        $products = $this->getCachedData($cacheKey, function () {
            return Product::with('stock')->get();
        });

        return response()->json($products, 200);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->only(['name', 'price', 'description']));
        $product->stock()->create(['quantity' => $request->quantity ?? 0]);

        // Clear products cache
        Redis::del('products.all');

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $cacheKey = "product:{$product->id}";

        $productData = $this->getCachedData($cacheKey, function () use ($product) {
            return $product->load('stock');
        });

        return response()->json($productData, 200);
    }


    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        $product->stock->update([
            'quantity' => $request['quantity'],
        ]);

        // Clear Redis cache for the product
        Redis::del("product:{$product->id}");

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        // Clear Redis cache for the product
        Redis::del("product:{$product->id}");

        return response()->json(null, 204);
    }
}
