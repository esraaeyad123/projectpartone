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
        $projects = Project::with(['customer'])->get();

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

        $invoice = Invoice::create($data);

        return response()->json([
            'message' => 'Invoice created successfully ✅',
            'invoice' => $invoice->load(['customer', 'project'])
        ], 201);
    }

    /**
     * ✏️ تعديل فاتورة
     */
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'invoice_date' => 'nullable|date',
            'department' => 'nullable|string',
            'prof_date' => 'nullable|date',
            'account_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'vat_profile' => 'nullable|string',
            'discount_pct' => 'nullable|numeric',
            'sales_tax_pct' => 'nullable|numeric',
            'retention_pct' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $invoice->update($data);

        return response()->json([
            'message' => 'Invoice updated successfully ✅',
            'invoice' => $invoice->load(['customer', 'project'])
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
}
