<?php

namespace App\Http\Controllers\Quotation;
use App\Models\PriceList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PriceListController extends Controller
{



  public function index(Request $request)
{
    // جلب كل العناصر من جدول PriceList
    $priceLists = PriceList::all();

    // إعادة JSON مناسب للـ DataTables
    return response()->json([
        'data' => $priceLists
    ]);
}
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'method' => 'nullable|string',
        'unit' => 'nullable|string',
        'price' => 'required|numeric',
        'price_only' => 'boolean',
        'active' => 'boolean',
    ]);

    // لو ما وصل service_id من الفورم → نولده أوتوماتيكياً
    $serviceId = $request->service_id ?: 'SRV-' . strtoupper(Str::random(6));

    // تأكد إنه مش موجود
    if (PriceList::where('service_id', $serviceId)->exists()) {
        return response()->json([
            'success' => false,
            'message' => "Service ID already exists: $serviceId"
        ], 400);
    }

    $priceItem = PriceList::create([
        'service_id' => $serviceId,
        'name' => $validated['name'],
        'method' => $validated['method'] ?? null,
        'unit' => $validated['unit'] ?? null,
        'price' => $validated['price'],
        'price_only' => $validated['price_only'] ?? false,
        'active' => $validated['active'] ?? true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Price item saved successfully!',
        'data' => $priceItem
    ]);
}

public function getSelected(Request $request)
{
    $ids = $request->ids ?? [];
    $items = PriceList::whereIn('id', $ids)->get();
    return response()->json(['data' => $items]);
}

public function setPriceOnly(Request $request)
{
    $validated = $request->validate([
        'ids' => 'required|array|min:1',
        'ids.*' => 'exists:price_lists,id',
    ]);

    \App\Models\PriceList::whereIn('id', $validated['ids'])
        ->update(['price_only' => true]);

    return response()->json([
        'success' => true,
        'message' => count($validated['ids']) . ' عنصر تم تعيينه كـ Price Only بنجاح.',
    ]);
}



}
