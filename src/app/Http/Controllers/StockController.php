<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendLowStockNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function show(Product $product)
    {
        try {
            if (!$product->stock) {
                return response()->json(['message' => 'Stock not found for the specified product.'], 404);
            }

            return response()->json($product->stock, 200);
        } catch (\Exception $e) {
            // Catch any unexpected errors
            return response()->json([
                'message' => 'An error occurred while retrieving the stock.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            // Validate the input
            $validated = $request->validate(['quantity' => 'required|integer']);

            // Check if stock exists
            if (!$product->stock) {
                return response()->json(['message' => 'Stock not found for the specified product.'], 404);
            }

            // Update the stock quantity
            $product->stock()->update(['quantity' => $validated['quantity']]);

            if($product->stock->quantity < Product::LOW_STOCK_THRESHOLD) {
                // Send email notification
                SendLowStockNotification::dispatch($product);
            }

            Redis::del("product:{$product->id}");

            return response()->json($product->stock, 200);

        } catch (\Exception $e) {
            // Catch any unexpected errors
            return response()->json([
                'message' => 'An error occurred while updating the stock.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendLowStockNotification($product)
    {
        $this->rabbitMQService->publishMessage('low_stock_queue', [
            'id' => $product->id,
            'name' => $product->name,
            'stock' => $product->stock,
        ]);

        return response()->json(['message' => 'Low stock notification sent']);
    }
}
