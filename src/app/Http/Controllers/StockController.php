<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockController extends Controller
{
    public function show(Product $product)
    {
        try {
            // Attempt to retrieve the stock
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
}
