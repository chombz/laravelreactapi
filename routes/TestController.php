<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'API is working!',
            'timestamp' => now(),
            'sanctum_installed' => class_exists('Laravel\Sanctum\Sanctum'),
        ]);
    }
}
