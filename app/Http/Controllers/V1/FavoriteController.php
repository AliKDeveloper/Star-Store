<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $user = $request->user();
        $favorites = $user->favorites()->paginate($perPage);

        return response()->json($favorites);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $product_id = $request->product_id;

        // Check if product ID is not null
        if (!$product_id)
        {
            return response()->json([
                'message' => 'Product ID is required',
            ], 404);
        }

        if ($user->favorites()->where('product_id', $product_id)->exists())
        {
            return response()->json([
                'message' => 'Product already exists in favorites',
            ], 409);
        }

        // The syncWithoutDetaching() method only adds a new record if it does not already exist in the pivot table.
        $user->favorites()->syncWithoutDetaching($product_id);

        return response()->json([
            'message' => 'Product added to favorites successfully',
        ], 200);
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        if (!$user->favorites()->where('product_id', $product->id)->exists())
        {
            return response()->json([
                'message' => 'Product does not exist in favorites',
            ], 404);
        }
        $user->favorites()->detach($product->id);

        return response()->json([
            'message' => 'Product removed from favorites successfully',
        ], 200);
    }

    public function destroyAll(Request $request)
    {
        $user = $request->user();
        $user->favorites()->detach();

        if ($user->favorites->isEmpty())
        {
            return response()->json(['message' => 'Favorite is empty']);
        }

        return response()->json([
            'message' => 'All products removed from favorites successfully',
        ], 200);
    }

    public function toggleFavorite(Request $request)
    {
        $user = auth()->user();
        $product_id = $request->product_id;
        // Check if product is not null
        if (!$product_id)
        {
            return response()->json([
                'message' => 'Product ID is required',
            ], 404);
        }

        if ($user->favorites()->where('product_id', $product_id)->exists()) {
            $user->favorites()->detach($product_id);

            return response()->json([
                'message' => 'Product removed from favorites successfully',
            ], 200);
        } else {
            $user->favorites()->attach($product_id);

            return response()->json([
                'message' => 'Product added to favorites successfully',
            ], 200);
        }
    }

}
