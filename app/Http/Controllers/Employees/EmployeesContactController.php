<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeContact;
use App\Models\Employee;

class EmployeesContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($employeeId)
    {
        $contacts = EmployeeContact::where('employee_id', $employeeId)->get();
        return response()->json($contacts);
    }

    /**
     * إنشاء contact جديد لموظف
     */
    public function store(Request $request, $employeeId)
    {
        $data = $request->all();
        $data['employee_id'] = $employeeId;

        $contact = EmployeeContact::create($data);

        return response()->json([
            'success' => true,
            'message' => '✅ Employee contact created successfully',
            'contact' => $contact
        ]);
    }

    /**
     * عرض contact محدد
     */
    public function show($id)
    {
        $contact = EmployeeContact::findOrFail($id);
        return response()->json($contact);
    }

    /**
     * تعديل contact لموظف
     */
    public function update(Request $request, Employee $employee, EmployeeContact $contact)
    {
        $contact->name       = $request->name;
        $contact->email      = $request->email;
        $contact->phone      = $request->phone;
        $contact->mobile     = $request->mobile;
        $contact->position   = $request->position;
        $contact->is_primary = $request->is_primary;
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Employee contact updated successfully',
            'contact' => $contact
        ]);
    }

    /**
     * حذف contact واحد
     */
    public function destroy($id)
    {
        $contact = EmployeeContact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Employee contact deleted']);
    }

    /**
     * حذف أكثر من contact مرة واحدة
     */
    public function deleteMultiple(Request $request)
{
    $ids = $request->input('ids', []);

    // تحقق أن البيانات مصفوفة وليست فاضية
    if (empty($ids) || !is_array($ids)) {
        return response()->json([
            'success' => false,
            'message' => '⚠️ لم يتم إرسال أي معرفات (IDs) للحذف'
        ], 400);
    }

    try {
        // حذف جهات الاتصال
        EmployeeContact::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => '✔️ تم حذف جهات الاتصال المحددة بنجاح'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '❌ حدث خطأ أثناء الحذف: ' . $e->getMessage()
        ], 500);
    }
}

}
