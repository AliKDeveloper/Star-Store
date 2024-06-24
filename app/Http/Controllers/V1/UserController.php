<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function delete(Request $request)
    {
        auth()->user()->delete();

        return response()->json([
            'message' => 'user deleted successfully',
        ], 200);

    }
}
