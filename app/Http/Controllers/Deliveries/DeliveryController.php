<?php

namespace App\Http\Controllers\Deliveries;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Project;
use App\Models\Customer;
use PDF;
use Illuminate\Support\Facades\Mail;

class DeliveryController extends Controller
{
public function index(Request $request)
{
        if ($request->expectsJson()) {
        // 📦 جلب كل عمليات التسليم مع العلاقات (العميل والمشروع)
        return Delivery::with(['customer', 'project'])->get();
    }

    // 🖥️ عرض البيانات في صفحة Blade
    $deliveries =Delivery::with(['customer', 'project'])->get();
    $customers = Customer::with(['contacts', 'projects', 'confirmations', 'deliveries', 'invoices'])->get();
    $projects = Project::with(['contacts', 'customer'])->get();
    return view('deliveries.index', compact('deliveries', 'customers', 'projects'));
}

public function store(Request $request)
{
     try {
        $delivery = Delivery::create($request->all());

        return response()->json([
            'status' => 'success',
            'id' => $delivery->id
        ]);

    } catch (\Exception $e) {
        // ارجع JSON بدل صفحة خطأ
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}


public function show($id)
{
    $delivery = Delivery::with(['project', 'customer'])->findOrFail($id);
    return response()->json($delivery);
}

public function update(Request $request, $id)
{
    try {
        // ✅ جلب السجل الحالي أو فشل إن لم يوجد
        $delivery = Delivery::findOrFail($id);

        // ✅ التحقق من الحقول المطلوبة
        $validated = $request->validate([
            'delivery_no'    => 'required|string|max:50',
            'delivery_date'  => 'required|date',
            'department'     => 'required|string|max:100',
            'project_code'   => 'nullable|string|max:100',
            'project_name'   => 'nullable|string|max:255',
            'customer_id'    => 'nullable|integer|exists:customers,id',
            'prepared_by'    => 'nullable|string|max:100',
            'delivered_by'   => 'nullable|string|max:100',
            'received_by'    => 'nullable|string|max:100',
            'date_received'  => 'nullable|date'
        ]);

        // ✅ تحديث البيانات
        $delivery->update($validated);

        // ✅ إرجاع استجابة ناجحة بصيغة JSON
        return response()->json([
            'message' => 'تم تحديث بيانات التسليم بنجاح ✅',
            'data' => $delivery
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // ⚠️ أخطاء التحقق
        return response()->json([
            'message' => 'خطأ في التحقق من البيانات',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        // ❌ أي خطأ آخر (Exception)
        return response()->json([
            'message' => 'حدث خطأ أثناء تحديث بيانات التسليم',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function destroy(Request $request, $id = null)
{
    try {
        // 🧠 دعم الحذف الجماعي أو الفردي
        $ids = $request->input('ids', []);
        if ($id) {
            $ids[] = $id;
        }

        if (empty($ids)) {
            return response()->json([
                'message' => 'لم يتم تحديد أي سجلات للحذف'
            ], 400);
        }

        // ⚠️ حذف السجلات المطلوبة
        Delivery::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'تم حذف عمليات التسليم بنجاح ✅'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ أثناء عملية الحذف ❌',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getProjectsWithRelations()
{
    try {
        // 🧠 إحضار جميع المشاريع مع العلاقات (العميل + الاتصالات)
        $projects = Project::with(['customer', 'contacts'])->get();

        return response()->json($projects, 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ أثناء تحميل المشاريع',
            'error' => $e->getMessage()
        ], 500);
    }
}



// DeliveryController.php
public function approvedeL(Delivery $delivery)
{
    try {
        $delivery->status = 'approved'; // أو أي حقل حالة عندك
        $delivery->save();

        return response()->json([
            'message' => 'تم اعتماد التسليم بنجاح ✅',
            'delivery' => $delivery
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ أثناء اعتماد التسليم ❌',
            'error' => $e->getMessage()
        ], 500);
    }
}




public function sendToCustomer(Delivery $delivery)
{
    // التحقق من وجود البريد الإلكتروني في الحقل address_email
    if (!$delivery->address_email) {
        return response()->json([
            'message' => 'لا يوجد بريد إلكتروني صالح في الحقل address_email'
        ], 400);
    }

    $customerEmail = $delivery->address_email;

    // توليد PDF للفاتورة
    $pdf = PDF::loadView('deliveries.invoice', compact('delivery'));

    // إرسال البريد
    Mail::send([], [], function ($message) use ($customerEmail, $pdf, $delivery) {
        $message->to($customerEmail)
            ->subject("Delivery Invoice: {$delivery->delivery_no}")
            ->attachData($pdf->output(), "Delivery-{$delivery->delivery_no}.pdf");
    });

    return response()->json([
        'message' => 'تم إرسال الفاتورة للعميل بنجاح ✅'
    ]);
}

}
