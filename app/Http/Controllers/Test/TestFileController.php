<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TestFileController extends Controller
{
    public function files($testId)
    {
        // جلب الاختبار مع الملفات المرتبطة به
        $test = Test::with('files')->findOrFail($testId);

        // عرض صفحة ملفات الاختبار وتمرير البيانات
        return view('testServicesAndCategory.test-files', compact('test'));
    }

     // رفع ملف واحد للاختبار
    public function store(Request $request, $testId)
{
    $request->validate([
        'file' => 'required|file|max:10240'
    ]);

    $file = $request->file('file');
    $path = $file->store("tests/{$testId}", 'public');

    $testFile = TestFile::create([
        'test_id'   => $testId,
        'file_name' => $file->getClientOriginalName(),
        'file_path' => $path,
        'file_type' => $file->getClientOriginalExtension(),
    ]);

    return response()->json($testFile, 201);
}


    // رفع ملف (طريقة ثانية)
    public function upload(Request $request, $testId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("tests/{$testId}", 'public'); // تخزين في public disk

        $testFile = TestFile::create([
            'test_id' => $testId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
        ]);

        return response()->json($testFile);
    }

    // جلب ملفات اختبار
    public function filesJson($testId)
    {
        $files = TestFile::where('test_id', $testId)->get();
        return response()->json($files);
    }


    public function view($id)
{
    $file = TestFile::findOrFail($id);

    $disk = Storage::disk('public');

    if (!$disk->exists($file->file_path)) {
        abort(404, 'الملف غير موجود.');
    }

    // عرض الملف مباشرة في المتصفح
    return response()->file($disk->path($file->file_path));
}

    // تحميل ملف واحد
    public function download($id)
    {
        $file = TestFile::findOrFail($id);

        $disk = Storage::disk('public'); // استخدام public disk

        if (!$disk->exists($file->file_path)) {
            abort(404, 'الملف غير موجود.');
        }

        return $disk->download($file->file_path, $file->file_name);
    }

    // تحميل عدة ملفات بملف ZIP
    public function downloadMultipleFiles(Request $request)
    {
        $fileIds = $request->input('file_ids', []);
        $testName = $request->input('test_name', 'Test');

        if (empty($fileIds)) {
            return response()->json(['error' => 'لم يتم تحديد ملفات.'], 400);
        }

        $files = TestFile::whereIn('id', $fileIds)->get();

        if ($files->isEmpty()) {
            return response()->json(['error' => 'الملفات غير موجودة.'], 404);
        }

        $zipName = $testName . '_Files.zip';
        $zip = new ZipArchive;
        $tempFile = tempnam(sys_get_temp_dir(), $zipName);

        if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $path = storage_path("app/{$file->file_path}");
                if (file_exists($path)) {
                    $zip->addFile($path, $file->file_namw);
                }
            }
            $zip->close();
        }

        return response()->download($tempFile, $zipName)->deleteFileAfterSend(true);
    }

    // حذف ملف واحد
    public function destroy(TestFile $file)
    {
        try {
            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }
            $file->delete();

            return response()->json(['success' => true, 'message' => 'تم حذف الملف بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // حذف ملفات متعددة
    public function destroyMultiple(Request $request)
    {
        $fileIds = $request->input('file_ids', []);

        if (empty($fileIds)) {
            return response()->json(['success' => false, 'message' => 'لم يتم تحديد ملفات للحذف.'], 400);
        }

        $files = TestFile::whereIn('id', $fileIds)->get();

        try {
            foreach ($files as $file) {
                if (Storage::exists($file->file_path)) {
                    Storage::delete($file->file_path);
                }
                $file->delete();
            }

            return response()->json(['success' => true, 'message' => 'تم حذف الملفات بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
