<?php

namespace App\Http\Controllers;

use App\Models\Coffee;
use Illuminate\Http\JsonResponse;

class CoffeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $coffees = Coffee::query()->get();
        return response()->json([
            'data' => [
                'coffees' => $coffees
            ]
        ]);
    }
}
