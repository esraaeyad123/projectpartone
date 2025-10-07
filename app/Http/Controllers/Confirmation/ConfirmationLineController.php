<?php

namespace App\Http\Controllers\Confirmation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Confirmation;
use App\Models\Project ;
use App\Models\ConfirmationLine;
use Illuminate\Support\Facades\DB;



class ConfirmationLineController extends Controller
{

public function store(Request $request)
{
    // ✅ تحقق من البيانات قبل الحفظ
    $validated = $request->validate([
        'confirmation_id' => 'required|exists:confirmations,id',
        'service_name' => 'required|string|max:255',
        'method' => 'nullable|string|max:255',
        'unit' => 'nullable|string|max:50',
        'quantity' => 'required|numeric|min:1',
        'price' => 'required|numeric|min:0',
        'total' => 'nullable|numeric|min:0',
    ]);

    // ✅ إنشاء السطر
    $line = ConfirmationLine::create($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Line saved successfully.',
        'line' => $line
    ]);
}




}
