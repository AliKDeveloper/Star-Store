<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page',15);
        $brands = Brand::paginate($per_page);

        return BrandResource::collection($brands);
    }
}
