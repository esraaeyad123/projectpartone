<?php

namespace App\Http\Controllers;
use App\Models\EmployeeFile;
namespace App\Http\Controllers\Employees;
use App\Http\Controllers\Controller;
use App\Models\EmployeeFile;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class EmployeeFileController extends Controller
{
      // عرض صفحة ملفات الموظف
    public function index($employeeId)
    {
        $employee = Employee::with('files')->findOrFail($employeeId);
        return view('employees.employee-files', compact('employee'));
    }

    // رفع ملف واحد
    public function store(Request $request, $employeeId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("employees/{$employeeId}", 'public');

        $employeeFile = EmployeeFile::create([
            'employee_id' => $employeeId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize() / 1024 // بالكيلوبايت
        ]);

        return response()->json($employeeFile, 201);
    }

    // رفع ملف (اختياري، نفس store)
    public function upload(Request $request, $employeeId)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store("employees/{$employeeId}", 'public');

        $employeeFile = EmployeeFile::create([
            'employee_id' => $employeeId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
        ]);

        return response()->json($employeeFile);
    }

    // جلب ملفات الموظف بصيغة JSON
    public function filesJson($employeeId)
    {
        $files = EmployeeFile::where('employee_id', $employeeId)->get();
        return response()->json($files);
    }

    // تنزيل ملف واحد
    public function download($id)
    {
        $file = EmployeeFile::findOrFail($id);
        $disk = Storage::disk('public');

        if (!$disk->exists($file->path)) {
            abort(404, 'الملف غير موجود.');
        }

        return $disk->download($file->path, $file->name);
    }

    // تنزيل ملفات متعددة كـ ZIP
    public function downloadMultipleFiles(Request $request)
    {
        $fileIds = $request->input('file_ids', []);
        $employeeName = $request->input('employee_name', 'Employee');

        if (empty($fileIds)) {
            return response()->json(['error' => 'لم يتم تحديد ملفات.'], 400);
        }

        $files = EmployeeFile::whereIn('id', $fileIds)->get();

        if ($files->isEmpty()) {
            return response()->json(['error' => 'الملفات غير موجودة.'], 404);
        }

        $zipName = $employeeName . '_Files.zip';
        $zip = new ZipArchive;
        $tempFile = tempnam(sys_get_temp_dir(), $zipName);

        if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $path = storage_path("app/public/{$file->path}");
                if (file_exists($path)) {
                    $zip->addFile($path, $file->name);
                }
            }
            $zip->close();
        }

        return response()->download($tempFile, $zipName)->deleteFileAfterSend(true);
    }

 // في EmployeeFileController.php
          public function viewEmployeeFile($fileId)
{
    $file = EmployeeFile::findOrFail($fileId);

    // المسار داخل storage/app/public
    $filePath = storage_path('app/public/' . $file->path);

    if (!file_exists($filePath)) {
        abort(404, "الملف غير موجود.");
    }

    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // الملفات التي يمكن عرضها في المتصفح مباشرة
    $viewable = ['pdf','jpg','jpeg','png','gif'];

    if (in_array($extension, $viewable)) {
        // عرض الملف مباشرة في المتصفح
        return response()->file($filePath);
    } else {
        // تحميل الملف مباشرة للملفات الأخرى
        return response()->download($filePath, $file->name);
    }
}


    // حذف ملف واحد
   // حذف ملف واحد من قاعدة البيانات فقط
// حذف ملف واحد من قاعدة البيانات فقط
public function destroy($fileId)
{
    try {
        $file = EmployeeFile::findOrFail($fileId);
        $file->delete(); // حذف السجل من قاعدة البيانات فقط

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الملف  بنجاح'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

// حذف ملفات متعددة من قاعدة البيانات فقط
public function destroyMultiple(Request $request)
{
    $fileIds = $request->input('file_ids', []);

    if (empty($fileIds)) {
        return response()->json([
            'success' => false,
            'message' => 'لم يتم تحديد ملفات للحذف.'
        ], 400);
    }

    try {
        EmployeeFile::whereIn('id', $fileIds)->delete(); // حذف السجلات مباشرة

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الملفات المحددة  بنجاح'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


}
