<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeesController extends Controller
{
    /**
     * عرض صفحة الموظفين
     */
       public function index(Request $request)
{
    if ($request->expectsJson()) {
        // جلب جميع الموظفين بصيغة JSON
        return Employee::all();
    }

    // إذا كان طلب عادي، عرض الصفحة مع بيانات الموظفين (يمكنك تمرير بيانات إضافية إذا أحببت)
    $employees = Employee::all();
    return view('employees.index', compact('employees'));
}




    /**
     * إضافة موظف جديد
     */
    public function store(Request $request)
    {
          $validatedData = $request->validate([
    'initials'       => 'nullable|string|max:10',
    'first_name'     => 'required|string|max:255',
    'mid_name'       => 'nullable|string|max:255',
    'last_name'      => 'required|string|max:255',
    'full_name'      => 'required|string|max:255',
    'email'          => 'nullable|email|max:255',
    'title'          => 'nullable|string|max:255',
    'supervisor_id'  => 'nullable|integer|exists:employees,id',
    'ctta'           => 'nullable|string|max:255',
    'business_unit'  => 'nullable|string|max:255',
    'department'     => 'nullable|string|max:255',
]);
$validatedData['job_rules'] = $request->job_roles ?? '';


        $employee = Employee::create($validatedData);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Employee created successfully',
             'id' => $employee->id,   // ✅ رجّع ID
              'employee' => $employee
        ]);
    }

    /**
     * تعديل بيانات موظف
     */
     public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $validatedData = $request->validate([
        'initials'       => 'nullable|string|max:10',
        'first_name'     => 'required|string|max:255',
        'mid_name'       => 'nullable|string|max:255',
        'last_name'      => 'required|string|max:255',
        'full_name'      => 'required|string|max:255',
        'email'          => 'nullable|email|max:255',
        'title'          => 'nullable|string|max:255',
        'supervisor_id'  => 'nullable|integer|exists:employees,id',
        'ctta'           => 'nullable|string|max:255',
        'business_unit'  => 'nullable|string|max:255',
        'department'     => 'nullable|string|max:255',
        'job_rules'      => 'nullable|string|max:255',
    ]);

    // تحويل job_roles من الجافاسكريبت إلى job_rules قبل الحفظ
    if ($request->has('job_roles')) {
    $validatedData['job_rules'] = $request->job_roles;
}


    $employee->update($validatedData);

    return response()->json([
        'status'   => 'success',
        'message'  => 'Employee updated successfully',
        'employee' => $employee,
        'id' => $employee->id,
    ]);
}


    /**
     * حذف موظف
     */
       public function show(Employee $employee)
{
    // إذا عندك علاقات، يمكنك تحميلها هنا، مثلاً: $employee->load('supervisor');
    return response()->json($employee);
}

public function destroy(Employee $employee)
{
    $employee->delete();
    return response()->json(['message' => 'Deleted']);
}

public function deleteMultiple(Request $request)
{
    $ids = $request->ids;
    if ($ids && is_array($ids)) {
        Employee::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    return response()->json(['message' => 'No employees selected'], 400);
}
}
