<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 15);
        $cartItems = $user->cartItems()->paginate($perPage);
        $cartResource = CartResource::collection($cartItems);

        return ['data' => $cartResource,'details' => ['shipping_fee'=>'a','subtotal'=>'b','total'=>'c']];
       // return CartResource::collection($cartItems)
//            ->additional(['data' =>
//                    ['details' => ['shipping_fee'=>'a','subtotal'=>'b','total'=>'c']
//                    ]
//                ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $product = Product::find($request->input('product_id'));

        // Check if product ID is not null
        if (!$request->input('product_id'))
        {
            return response()->json([
                'message' => 'Product ID is required',
            ], 404);
        }
        // Check if product is existed in the database
        if (!$product)
        {
            return response()->json([
                'message' => 'Product ID is not exist in the database',
            ], 404);
        }

        //Check if product is already in cart
        if ($user->cartItems()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'message' => 'Product already exists in cart',
            ], 409);
        }
        else{
            //Check Product Stock
            if ($product->stock < 1) {
                return response()->json([
                    'message' => 'The product is out of stock',
                ], 400);
            }

            // Add product to cart
            $user->cartItems()->attach($product->id,
                [
                    'quantity' => 1,
                    'total_price' => $product->price
                ]);
            $product->decrement('stock', 1);
            $product->save();

            return response()->json(['message' => 'Product added to cart']);
        }
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();
        $product = Product::find($product->id);

        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem?->exists())
        {
            $product->increment('stock', $cartItem->pivot->quantity);
            $product->save();

            $user->cartItems()->detach($product->id);

            return response()->json(['message' => 'Product removed from cart']);
        }
        else
        {
            return response()->json(['message' => 'Product not exists in cart'], 404);
        }


    }

    public function destroyAll(Request $request)
    {
        $user = $request->user();

        // Retrieve all cart items for the user
        $cartItems = $user->cartItems;

        if ($cartItems->isEmpty())
        {
            return response()->json(['message' => 'Cart is empty']);
        }

        // Loop through each cart item and remove it from the cart
        foreach ($cartItems as $cartItem) {
            $product_id = $cartItem->pivot->product_id;

            // Increment the stock of the product
            Product::where('id', $product_id)
                ->first()
                ->increment('stock', $cartItem->pivot->quantity);

            // Delete the cart item
            $user->cartItems()->detach($product_id);
        }

        return response()->json(['message' => 'All products removed from cart']);
    }

    public function increaseQuantity(Request $request)
    {
        $user = auth()->user();
        $product = Product::find($request->input('product_id'));

        // Check if product ID is not null
        if (!$product)
        {
            return response()->json([
                'message' => 'Product ID is required',
            ], 404);
        }

        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if (!$cartItem)
        {
            return response()->json([
                'message' => 'Product not exists in cart',
            ], 404);
        }

        //Check Product Stock
        if ($product->stock >= 1) {
            $cartItem->pivot->increment('quantity', 1);
            $cartItem->pivot->total_price = $cartItem->pivot->quantity * $product->price;
            $product->decrement('stock', 1);

            return response()->json([
                'message' => 'Quantity increased successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'The product is out of stock',
            ], 400);
        }
    }

    public function decreaseQuantity(Request $request)
    {
        $user = auth()->user();
        $product = Product::find($request->input('product_id'));

        // Check if product ID is not null
        if (!$product)
        {
            return response()->json([
                'message' => 'Product ID is required',
            ], 404);
        }

        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if (!$cartItem)
        {
            return response()->json([
                'message' => 'Product not exists in cart',
            ], 404);
        }

        if ($cartItem->pivot->quantity == 1)
        {
            $user->cartItems()->detach($product->id);

            $product->increment('stock', 1);

            return response()->json([
                'message' => 'The product has been removed successfully from the cart',
            ], 200);
        }

        $cartItem->pivot->decrement('quantity', 1);
        $cartItem->pivot->total_price = $cartItem->pivot->quantity * $product->price;
        $product->increment('stock', 1);

        return response()->json([
            'message' => 'Quantity decreased successfully',
        ], 200);
    }

}
