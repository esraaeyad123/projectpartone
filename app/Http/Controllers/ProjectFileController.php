<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectFileController extends Controller
{
     public function index($projectId)
    {
        $project = Project::with('files')->findOrFail($projectId);


        return view('projects.project-files', compact('project'));

    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // max 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("projects/{$projectId}", 'public');

        $projectFile = ProjectFile::create([
            'project_id' => $projectId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize() / 1024 // بالكيلوبايت
        ]);

        return response()->json($projectFile, 201);
    }



    public function upload(Request $request, $projectId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("projects/{$projectId}", 'public'); // تخزين في public disk

        $projectFile = ProjectFile::create([
            'project_id' => $projectId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getClientMimeType(),
        ]);

        return response()->json($projectFile);
    }

    // جلب ملفات المشروع
    public function filesJson($projectId)
    {
        $files = ProjectFile::where('project_id', $projectId)->get();
        return response()->json($files);
    }




public function download($id)
{
    $file = ProjectFile::findOrFail($id);

    $disk = Storage::disk('public'); // استخدام public disk

    if (!$disk->exists($file->path)) {
        abort(404, 'الملف غير موجود.');
    }

    return $disk->download($file->path, $file->name);
}

public function downloadMultipleFiles(Request $request)
{
    $fileIds = $request->input('file_ids', []);
    $projectName = $request->input('project_name', 'Project');

    if (empty($fileIds)) {
        return response()->json(['error' => 'لم يتم تحديد ملفات.'], 400);
    }

    $files = ProjectFile::whereIn('id', $fileIds)->get();

    if ($files->isEmpty()) {
        return response()->json(['error' => 'الملفات غير موجودة.'], 404);
    }

    $zipName = $projectName . '_Files.zip';
    $zip = new ZipArchive;
    $tempFile = tempnam(sys_get_temp_dir(), $zipName);

    if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {
        foreach ($files as $file) {
            $path = storage_path("app/{$file->path}"); // تأكد أن المسار صحيح
            if (file_exists($path)) {
                $zip->addFile($path, $file->name);
            }
        }
        $zip->close();
    }

    return response()->download($tempFile, $zipName)->deleteFileAfterSend(true);
}


  public function destroy(ProjectFile $file)
    {
        try {
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            $file->delete();

            return response()->json(['success' => true]);
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

        $files = ProjectFile::whereIn('id', $fileIds)->get();

        try {
            foreach ($files as $file) {
                if (Storage::exists($file->path)) {
                    Storage::delete($file->path);
                }
                $file->delete();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
