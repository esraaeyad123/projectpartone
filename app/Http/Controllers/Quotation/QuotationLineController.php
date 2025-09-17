<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationLine;
use Illuminate\Support\Facades\DB;


class QuotationLineController extends Controller
{


      public function getByQuotation($quotationId)
    {
        $lines = QuotationLine::where('quotation_id', $quotationId)->get();
        return response()->json(['data' => $lines]);
    }

    // حفظ عناصر جديدة
    public function store(Request $request)
    {
        $request->validate([
            'lines' => 'required|array',
            'lines.*.quotation_id' => 'required|exists:quotation_headers,id',
            'lines.*.price_list_id' => 'nullable|exists:price_lists,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.category' => 'nullable|string',
            'lines.*.type' => 'nullable|string',
            'lines.*.method' => 'nullable|string',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.price' => 'required|numeric|min:0',
            'lines.*.total' => 'required|numeric|min:0',
        ]);

        $savedLines = [];
        foreach ($request->lines as $lineData) {
            $savedLines[] = QuotationLine::create($lineData);
        }

        return response()->json([
            'success' => true,
            'message' => count($savedLines) . " عنصر(عناصر) تمت إضافتها بنجاح!",
            'data' => $savedLines
        ]);
    }

    public function getLines()
    {

        $lines = QuotationLine::all();

        return response()->json($lines);


    }

    public function listLines($quotationId)
{
    $lines = QuotationLine::where('quotation_id', $quotationId)->get();
    return response()->json($lines);
}
public function storeLine(Request $request, $quotationId)
{
    $lines = [];
    foreach ($request->lines as $lineData) {
      $validated = $request->validate::make($lineData, [
            'description' => 'nullable|string|max:255',
            'category'    => 'nullable|string|max:100',
            'type'        => 'nullable|string|max:100',
            'method'      => 'nullable|string|max:100',
            'price'       => 'nullable|numeric|min:0',
            'quantity'    => 'nullable|numeric|min:1',
            'price_list_id' => 'required|integer'
        ])->validated();

        $line = QuotationLine::create([
            'quotation_id' => $quotationId,
            'price_list_id' => $validated['price_list_id'],
            'description'  => $validated['description'] ?? '',
            'category'     => $validated['category'] ?? null,
            'type'         => $validated['type'] ?? null,
            'method'       => $validated['method'] ?? null,
            'price'        => $validated['price'] ?? 0,
            'quantity'     => $validated['quantity'] ?? 1,
            'total'        => ($validated['price'] ?? 0) * ($validated['quantity'] ?? 1),
        ]);

        $lines[] = $line;
    }

    return response()->json([
        'success' => true,
        'lines' => $lines
    ]);
}






public function updateLine(Request $request, $lineId)
{
    $line = QuotationLine::findOrFail($lineId);
    $line->update($request->all());

    return response()->json([
        'success' => true,
        'line' => $line
    ]);
}

public function deleteLine($lineId)
{
    $line = QuotationLine::findOrFail($lineId);
    $line->delete();

    return response()->json(['success' => true, 'message' => 'Line deleted']);
}
}
