<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\RabbitMQService;
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
            // Attempt to retrieve the stock
            if (!$product->stock) {
                return response()->json(['message' => 'Stock not found for the specified product.'], 404);
            }
            // send low stock notification
            if ($product->stock->quantity < 10) {
                $this->sendLowStockNotification($product);
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

            // Check if the stock is 5 or below
            if ($product->stock <= 5) {
                // Publish a message to RabbitMQ to send the notification
                $rabbitMQ = new RabbitMQService();
                $rabbitMQ->publishMessage('low_stock_queue', [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                ]);
            }

            return response()->json($product->stock, 200);
        } catch (ModelNotFoundException $e) {
            // Handle case when the product or stock is not found
            return response()->json(['message' => 'Product or stock not found.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
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
