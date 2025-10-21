<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationLine;
use Illuminate\Support\Facades\Validator;

class QuotationLineController extends Controller
{

     public function index(Request $request)
    {
         $quotationId = $request->query('quotation_id');

    if (!$quotationId) {
        return response()->json(['error' => 'quotation_id is required'], 400);
    }

    $lines = QuotationLine::where('quotation_id', $quotationId)->get();

    return response()->json($lines); // تأكد أنها مصفوفة من الكائنات
    }
    /**
     * 🔹 إرجاع جميع البنود لجميع العروض (لأغراض الإدارة)
     */
    public function getLines()
    {
        $lines = QuotationLine::all();
        return response()->json(['data' => $lines]);
    }

    /**
     * 🔹 إرجاع البنود الخاصة بعرض سعر معين
     */
    public function getByQuotation($quotationId)
    {
        $lines = QuotationLine::where('quotation_id', $quotationId)->get();
        return response()->json(['data' => $lines]);
    }

    /**
     * 🔹 حفظ بند/بنود جديدة (باستخدام storeLine)
     */
    public function storeLine(Request $request)
    {
        $validated = $request->validate([
            'quotation_id'   => 'required|exists:quotation_headers,id',
            'lines'          => 'required|array|min:1',
            'lines.*.price_list_id' => 'nullable|exists:price_lists,id',
            'lines.*.description'   => 'nullable|string|max:255',
            'lines.*.category'      => 'nullable|string|max:100',
            'lines.*.type'          => 'nullable|string|max:100',
            'lines.*.method'        => 'nullable|string|max:100',
            'lines.*.quantity'      => 'required|numeric|min:1',
            'lines.*.price'         => 'required|numeric|min:0',
            'lines.*.total'         => 'nullable|numeric|min:0',
        ]);

        $quotationId = $validated['quotation_id'];
        $savedLines = [];

        foreach ($validated['lines'] as $lineData) {
            $lineData['quotation_id'] = $quotationId;
            $lineData['total'] = $lineData['total'] ?? ($lineData['price'] * $lineData['quantity']);
            $savedLines[] = QuotationLine::create($lineData);
        }

        return response()->json([
            'success' => true,
            'message' => count($savedLines) . " بند(بنود) تمت إضافتها بنجاح!",
            'data' => $savedLines
        ]);
    }

    /**
     * 🔹 إضافة مجموعة من البنود دفعة واحدة (Bulk Add)
     */
   public function bulkAdd(Request $request)
{
    $validated = $request->validate([
        'quotation_id' => 'required|exists:quotation_headers,id',
        'lines' => 'required|array|min:1',
        'lines.*.price_list_id' => 'nullable|exists:price_lists,id',
        'lines.*.description' => 'nullable|string|max:255',
        'lines.*.category' => 'nullable|string|max:100',
        'lines.*.type' => 'nullable|string|max:100',
        'lines.*.method' => 'nullable|string|max:100',
        'lines.*.quantity' => 'required|numeric|min:1',
        'lines.*.price' => 'required|numeric|min:0',
    ]);

    $quotationId = $validated['quotation_id'];
    $lines = $validated['lines'];

    $created = [];

    foreach ($lines as $lineData) {
        $line = new \App\Models\QuotationLine();
        $line->quotation_id = $quotationId;
        $line->price_list_id = $lineData['price_list_id'] ?? null;
        $line->description = $lineData['description'] ?? '';
        $line->category = $lineData['category'] ?? null;
        $line->type = $lineData['type'] ?? null;
        $line->method = $lineData['method'] ?? null;
        $line->quantity = $lineData['quantity'];
        $line->price = $lineData['price'];
        $line->total = $line->price * $line->quantity;
        $line->save();

        $created[] = $line;
    }

    return response()->json([
        'success' => true,
        'message' => count($created) . ' بند(بنود) تمت إضافتها بنجاح!',
        'lines' => $created
    ]);
}


public function bulkUpdate(Request $request)
{
    $quotationId = $request->quotation_id;
    $lines = $request->lines;

    foreach ($lines as $lineData) {
        $line = QuotationLine::updateOrCreate(
            ['id' => $lineData['id'] ?? null],
            [
                'quotation_id' => $quotationId,
                'price_list_id' => $lineData['price_list_id'],
                'description' => $lineData['description'],
                'category' => $lineData['category'],
                'type' => $lineData['type'],
                'method' => $lineData['method'],
                'accounted' => $lineData['accounted'] ?? 0,
                'quantity' => $lineData['quantity'] ?? 1,
                'price' => $lineData['price'] ?? 0,
            ]
        );
    }

    return response()->json(['success' => true, 'message' => 'Quote lines updated successfully']);
}


public function delete(Request $request)
{
    $ids = $request->ids;

    if (!$ids || !is_array($ids)) {
        return response()->json(['success' => false, 'message' => 'No IDs provided.'], 400);
    }

    QuotationLine::whereIn('id', $ids)->delete();

    return response()->json(['success' => true, 'message' => 'Quotation lines deleted successfully.']);
}


}
