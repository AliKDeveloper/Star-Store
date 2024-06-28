<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $brand_id = $request->input('brand_id');
        $category_id = $request->input('category_id');
        $is_featured = (boolean) $request->input('is_featured');
        $productsQuery = Product::query();

        if ($brand_id) {
            $productsQuery->orWhere('brand_id', $brand_id);
        }

        if ($category_id) {
            $productsQuery->orWhere('category_id', $category_id);
        }

        // Filter products by is_featured otherwise get all products
        if ($is_featured) {
            $productsQuery->where('is_featured', $is_featured);
        }

        $products = $productsQuery->paginate($perPage);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
