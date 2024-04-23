<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Coffee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SalesService;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $sales = Sale::query()
            ->leftJoin('coffees', 'sales.coffee_id', '=', 'coffees.id')
            ->orderBy('sales.id', 'desc')
            ->get();
        return response()->json([
            'data' => [
                'sales' => $sales
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product' => 'required|exists:App\Models\Coffee,id',
            'quantity' => 'required|numeric',
            'unit_cost' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        $newSale = Sale::query()->create([
            'quantity' => $request->input('quantity'),
            'unit_cost' => $request->input('unit_cost'),
            'selling_price' => $request->input('selling_price'),
            'coffee_id' => $request->input('product'),
        ]);

        if ($newSale) {
            return response()->json([
                'data' => [
                    'sale_id' => $newSale->id
                ]
            ]);
        }
    }

    /**
     * Calculate the selling price
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSellingPrice(Request $request): JsonResponse
    {
        $request->validate([
            'product' => 'required|exists:App\Models\Coffee,id',
            'quantity' => 'required|numeric',
            'unit_cost' => 'required|numeric',
        ]);

        $coffee = Coffee::query()->findOrFail($request->input('product'));
        $profitMargin = $coffee->profit_margin;

        $saleService = new SalesService(
            $request->input('quantity'),
            $request->input('unit_cost'),
            $profitMargin
        );
        $saleService->calculateSellingPrice();
        $sellingPrice = $saleService->getSellingPrice();

        return response()->json([
            'data' => [
                'selling_price' => $sellingPrice
            ]
        ]);
    }
}
