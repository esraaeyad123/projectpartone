<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Models\EquipmentFile;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class EquipmentFileController extends Controller
{
    // عرض صفحة ملفات الجهاز
    public function index($equipmentId)
    {
        $equipment = Equipment::with('files')->findOrFail($equipmentId);
        return view('equipments.equipment-files', compact('equipment'));
    }

    // رفع ملف واحد
    public function store(Request $request, $equipmentId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("equipments/{$equipmentId}", 'public');

        $equipmentFile = EquipmentFile::create([
            'equipment_id' => $equipmentId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize() / 1024 // بالكيلوبايت
        ]);

        return response()->json($equipmentFile, 201);
    }

    // جلب ملفات الجهاز بصيغة JSON
    public function filesJson($equipmentId)
    {
        $files = EquipmentFile::where('equipment_id', $equipmentId)->get();
        return response()->json($files);
    }

    // تنزيل ملف واحد
    public function download($id)
    {
        $file = EquipmentFile::findOrFail($id);
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
        $equipmentName = $request->input('equipment_name', 'Equipment');

        if (empty($fileIds)) {
            return response()->json(['error' => 'لم يتم تحديد ملفات.'], 400);
        }

        $files = EquipmentFile::whereIn('id', $fileIds)->get();

        if ($files->isEmpty()) {
            return response()->json(['error' => 'الملفات غير موجودة.'], 404);
        }

        $zipName = $equipmentName . '_Files.zip';
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

    // عرض ملف مباشرة (pdf, images) أو تحميله إذا نوع آخر
    public function viewEquipmentFile($fileId)
    {
        $file = EquipmentFile::findOrFail($fileId);
        $filePath = storage_path('app/public/' . $file->path);

        if (!file_exists($filePath)) {
            abort(404, "الملف غير موجود.");
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $viewable = ['pdf','jpg','jpeg','png','gif'];

        if (in_array($extension, $viewable)) {
            return response()->file($filePath);
        } else {
            return response()->download($filePath, $file->name);
        }
    }

    // حذف ملف واحد
public function destroy($fileId)
{
    $file = EquipmentFile::findOrFail($fileId);
    $disk = Storage::disk('public');

    // حذف الملف من السيرفر إذا كان موجود
    if ($disk->exists($file->path)) {
        $disk->delete($file->path);
    }

    // حذف السجل من قاعدة البيانات
    $file->delete();

    return response()->json([
        'success' => true,
        'message' => 'تم حذف الملف  بنجاح'
    ]);
}



    // حذف ملفات متعددة
 public function destroyMultiple(Request $request)
{
    $fileIds = $request->input('file_ids', []);

    if (empty($fileIds)) {
        return response()->json(['success' => false, 'message' => 'لم يتم تحديد ملفات للحذف.'], 400);
    }

    $files = EquipmentFile::whereIn('id', $fileIds)->get();

    try {
        foreach ($files as $file) {
            // حذف الملف من السيرفر إذا موجود
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            // حذف السجل من قاعدة البيانات
            $file->delete();
        }

        return response()->json(['success' => true, 'message' => 'تم حذف الملفات بنجاح.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


}
