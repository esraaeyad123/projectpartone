<?php
namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Customer;
class FinancialController extends Controller
{
    /**
     * عرض الصفحة المالية.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // ✅ لو الطلب من AJAX أو API يرجع JSON
        if ($request->expectsJson()) {
            return Invoice::with(['customer', 'project'])->get();
        }

        // ✅ للعرض داخل Blade
        $invoices = Invoice::with(['customer', 'project'])->get();
        $customers = Customer::all();
        $projects = Project::with(['customer' ,'contacts'])->get();

        return view('financial.index', compact('invoices', 'customers', 'projects'));
    }

    /**
     * 🆕 إنشاء فاتورة جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_date' => 'required|date',
            'department' => 'nullable|string',
            'prof_date' => 'nullable|date',
            'account_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'customer_id' => 'required|exists:customers,id',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'vat_profile' => 'nullable|string',
            'discount_pct' => 'nullable|numeric',
            'sales_tax_pct' => 'nullable|numeric',
            'retention_pct' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $invoice = Invoice::create($request->all());

        return response()->json([
            'message' => 'Invoice created successfully ✅',
            'invoice' => $invoice->load(['customer', 'project'])
        ], 201);
    }

    /**
     * ✏️ تعديل فاتورة
     */


            public function update(Request $request, $invoiceId)
{
    $invoice = Invoice::find($invoiceId);
    if (!$invoice) {
        return response()->json([
            'message' => '⚠️ الفاتورة غير موجودة.'
        ], 404);
    }

    $data = $request->only([
        'invoice_no', 'invoice_date', 'department', 'prof_date', 'account_date', 'due_date',
        'project_code', 'project_name', 'project_details', 'contract_no',
        'customer_id', 'account_no', 'trn_no', 'location',
        'account_manager', 'contact_person', 'contact_mobile',
        'attn_to', 'attn_pos', 'address_email',
        'payment_terms', 'payment_method', 'vat_profile',
        'discount_pct', 'sales_tax_pct', 'retention_pct', 'currency', 'status'
    ]);

    // تحقق من وجود تغييرات
    $changes = array_diff_assoc($data, $invoice->only(array_keys($data)));
    if (empty($changes)) {
        return response()->json([
            'message' => '⚠️ لم يتم تعديل أي بيانات — لم يحدث تغيير فعلي.'
        ], 422);
    }

    $invoice->update($data);

    return response()->json([
        'message' => '✅ تم تحديث الفاتورة بنجاح.',
        'invoice' => $invoice->fresh()->load(['customer', 'project'])
    ]);
}






    /**
     * 🗑️ حذف فاتورة
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json([
            'message' => 'Invoice deleted successfully 🗑️'
        ]);
    }


    public function show($id)
    {
        $invoice = Invoice::with('customer', 'project')->find($id);

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        return response()->json($invoice);
    }

    // ===============================
    // 3️⃣ الحصول على بنود فاتورة
    // ===============================
    public function getLines($id)
    {
        $lines = InvoiceLine::where('invoice_id', $id)->get();

        return response()->json($lines);
    }
}
