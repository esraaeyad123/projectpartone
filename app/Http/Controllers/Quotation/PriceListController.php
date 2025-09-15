<?php

namespace App\Http\Controllers\Quotation;
use App\Models\PriceList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
     public function index()
    {
        $prices = PriceList::all();
        return response()->json($prices);
    }
    public function store(Request $request)
{
     $price= $request->validate([
    'name' => 'required|string|max:255',
    'method' => 'required|string',
    'unit' => 'nullable|string',
    'price' => 'required|numeric',
    'price_only' => 'boolean',
    'active' => 'boolean',
              ]);
    PriceList::create($request->all());


    return response()->json($price, 201); // ترجع JSON
}
}
