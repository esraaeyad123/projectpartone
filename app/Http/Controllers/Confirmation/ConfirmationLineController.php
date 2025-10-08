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
         'price_only' => 'nullable|boolean', // ✅ إضافة هذا

    ]);

    // ✅ إنشاء السطر
    $line = ConfirmationLine::create($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Line saved successfully.',
        'line' => $line
    ]);
}

 public function update(Request $request, $id)
    {
        $line = ConfirmationLine::findOrFail($id);

        $validatedData = $request->validate([
            'service_id'  => 'nullable|integer',
            'service_name' => 'required|string|max:255',
            'method' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:1',
            'price_only' => 'boolean',
        ]);

        $line->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Confirmation line updated successfully',
            'line' => $line,
        ]);
    }


    public function destroy($id)
{
    // إيجاد الـ line أو فشل إذا لم يوجد
    $line = ConfirmationLine::findOrFail($id);

    try {
        $line->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف Service Line بنجاح ✅'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ أثناء الحذف.'
        ], 500);
    }
}

}
