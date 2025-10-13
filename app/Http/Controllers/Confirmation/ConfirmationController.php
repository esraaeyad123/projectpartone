<?php

namespace App\Http\Controllers\Confirmation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Confirmation;
use App\Models\Project ;
use App\Models\Customer ;
use App\Models\ConfirmationLine;
use Illuminate\Support\Facades\DB;



class ConfirmationController extends Controller
{
public function index(Request $request)
{
    if ($request->expectsJson()) {
        // جلب كل التعميدات مع العميل (مع جهات الاتصال) والمشروع
        return Confirmation::with([
            'customer.contacts', // تحميل العميل مع جهات الاتصال
            'project.contacts'   // تحميل المشروع مع جهات الاتصال
        ])->get();
    }

    // للعرض في صفحة Blade
    $confirmations = Confirmation::with([
        'customer.contacts', // العميل مع جهات الاتصال
        'project.contacts'   // المشروع مع جهات الاتصال
    ])->get();

    $customers = Customer::with('contacts')->get(); // العميل مع جهات الاتصال
    $projects = Project::with('contacts')->get();

    return view('confirmation.index', compact('confirmations', 'customers', 'projects'));
}



public function store(Request $request)
{
    $confirmation = Confirmation::create($request->all());
    return response()->json(['status' => 'success', 'id' => $confirmation->id]);
}

public function show($id)
{
    $confirmation = Confirmation::with(['customer', 'project' ,'lines'])->find($id);

    if (!$confirmation) {
        return response()->json(null, 404);
    }

    return response()->json($confirmation);
}


 public function getLines($id)
    {
        // جلب الـ Confirmation مع الـ lines
        $confirmation = Confirmation::with('lines')->find($id);

        if (!$confirmation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Confirmation not found'
            ], 404);
        }

        // تحويل الخطوط إلى مصفوفة مناسبة للـ DataTable
        $lines = $confirmation->lines->map(function($line) {
            return [
                'id' => $line->id,
                'service_name' => $line->service_name,
                'method' => $line->method,
                'unit' => $line->unit,
                'quantity' => $line->quantity,
                'price' => $line->price,
                'total' => $line->total,
                'price_only' => $line->price_only
            ];
        });

        return response()->json($lines);
    }

    public function update(Request $request, $id)
{
    // جلب الـ Confirmation أو فشل إذا لم يوجد
    $confirmation = Confirmation::findOrFail($id);

    // التحقق من صحة البيانات
    $validatedData = $request->validate([
        'customer_id'      => 'required|integer|exists:customers,id',
        'project_id'       => 'required|integer|exists:projects,id',
        'project_code'     => 'nullable|string|max:255',
        'project_name'     => 'nullable|string|max:255',
        'project_details'  => 'nullable|string|max:1000',
        'contact_person'   => 'nullable|string|max:255',
        'conf_to'          => 'nullable|string|max:255',
        'category'         => 'nullable|string|max:255',
        'confirm_date'     => 'nullable|date',
        'subject'          => 'nullable|string|max:255',
        'conf_source'      => 'nullable|string|max:255',
        'contract_no'      => 'nullable|string|max:255',
        'currency'         => 'nullable|string|max:10',
        'discount'         => 'nullable|numeric',
        'tax'              => 'nullable|numeric',
        'validity'         => 'nullable|string|max:50',
        'payment_terms'    => 'nullable|string|max:255',
    ]);

    // تحديث البيانات
    $confirmation->update($validatedData);

    // إعادة الاستجابة JSON
    return response()->json([
        'status'       => 'success',
        'message'      => 'Confirmation updated successfully',
        'confirmation' => $confirmation,
        'id'           => $confirmation->id,
    ]);
}


public function duplicate(Request $request)
{
    $id = $request->id;

    // التأكد من وجود الـ Confirmation
    $confirmation = Confirmation::with('lines')->find($id);
    if (!$confirmation) {
        return response()->json([
            'status' => 'error',
            'message' => 'لم يتم العثور على الـ Confirmation المحدد.'
        ], 404);
    }

    // نسخ الـ Confirmation
    $newConfirmation = $confirmation->replicate();

    // توليد رقم جديد تلقائي باستخدام العمود الموجود confirm_id
    $lastConfirmation = Confirmation::latest()->first();
    $newNumber = $lastConfirmation
        ? ((int) filter_var($lastConfirmation->confirm_id, FILTER_SANITIZE_NUMBER_INT) + 1)
        : 1;

    $newConfirmation->confirm_id = 'CONF-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    $newConfirmation->save();

    // نسخ خطوط الـ Confirmation
    foreach ($confirmation->lines as $line) {
        $newLine = $line->replicate();
        $newLine->confirmation_id = $newConfirmation->id; // هذا صحيح، يربط الخطوط بالـ Confirmation الجديد
        $newLine->save();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'تم نسخ الـ Confirmation بنجاح ✅',
        'newConfirmationId' => $newConfirmation->id,
        'newConfirmationNumber' => $newConfirmation->confirm_id
    ]);
}

public function destroy($id)
{
    $confirmation = Confirmation::find($id);
    if (!$confirmation) {
        return response()->json(['status' => 'error', 'message' => 'Confirmation not found'], 404);
    }

    $confirmation->delete();

    return response()->json(['status' => 'success', 'message' => 'Confirmation deleted']);
}


}
