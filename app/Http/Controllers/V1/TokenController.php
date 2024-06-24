<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{

    public function checkToken(Request $request)
    {

        if ($request->user()->currentAccessToken())
        {
            return response()->json(['status' => true, 'message' => 'Token Valid'], 200);
        }

//        else
//        {
//            return response()->json(['message' => 'Token Invalid'], 401);
//        }
    }
}
