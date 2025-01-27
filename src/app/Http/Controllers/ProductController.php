<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendLowStockNotification;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

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

    public function index(): JsonResponse
    {
        $cacheKey = "product:all";

        $products = $this->getCachedData($cacheKey, function () {
            return $this->productRepository->getAllProducts();
        });

        return response()->json($products, 200);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->createProduct($request->all());

        // Clear products cache
        Redis::del('product.all');

        return response()->json($product, 201);
    }

    public function show(Product $product): JsonResponse
    {
        $productData = $this->productRepository->getProductById($product);

        return response()->json($productData, 200);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productRepository->updateProduct($product, $request->all());

        if($product->stock->quantity < Product::LOW_STOCK_THRESHOLD) {
            // Send email notification
            SendLowStockNotification::dispatch($product);
        }

        // Clear Redis cache for the product
        Redis::del("product:{$product->id}");

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productRepository->deleteProduct($product);

        // Clear Redis cache for the product
        Redis::del("product:{$product->id}");

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
