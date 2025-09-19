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
    public function viewEmployees()
    {
        return view('employees.index');
    }

    /**
     * جلب بيانات الموظفين للجدول (DataTable)
     */
    public function getEmployeesData()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * جلب موظف واحد (للتعديل مثلاً)
     */
    public function getEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
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
            'job_rules'      => 'nullable|string|max:255',
        ]);

        $employee = Employee::create($validatedData);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Employee created successfully',
             'id' => $employee->id,   // ✅ رجّع ID
              'employee' => $employee
        ], 201);
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

        $employee->update($validatedData);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Employee updated successfully',
            'employee' => $employee ,
              'id' => $employee->id,
        ]);
    }

    /**
     * حذف موظف
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Employee deleted successfully'
        ]);
    }
}
