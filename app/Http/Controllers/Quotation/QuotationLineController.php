<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationLine;

class QuotationLineController extends Controller
{

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
    $line = QuotationLine::create([
        'quotation_id'    => $quotationId,
        'service_test_id' => $request->service_test_id,
        'description'     => $request->description,
        'accounted'       => $request->accounted ?? 0,
        'category'        => $request->category,
        'type'            => $request->type,
        'method'          => $request->method,
        'price'           => $request->price ?? 0,
    ]);

    return response()->json([
        'success' => true,
        'line' => $line
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
