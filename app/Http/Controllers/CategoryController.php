<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page',15);
        $categories = Category::paginate($per_page);

        return CategoryResource::collection($categories);
    }
}
