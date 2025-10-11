<?php

namespace App\Http\Controllers\Confirmation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Confirmation;
use App\Models\ConfirmationFile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ConfirmationFileController extends Controller
{

     public function filesJson($confirmationId)
    {
        $files = ConfirmationFile::where('confirmation_id', $confirmationId)->get();
        return response()->json($files);
    }

    public function store(Request $request, $confirmationId)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store("confirmations/{$confirmationId}", 'public');

        $confirmationFile = ConfirmationFile::create([
            'confirmation_id' => $confirmationId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize() / 1024
        ]);

        return response()->json($confirmationFile, 201);
    }

    public function viewFile($id)
    {
        $file = ConfirmationFile::findOrFail($id);
        return response()->file(storage_path("app/public/{$file->path}"));
    }

    public function download($id)
    {
        $file = ConfirmationFile::findOrFail($id);
        return response()->download(storage_path("app/public/{$file->path}"), $file->name);
    }

    public function destroy($id)
    {
        $file = ConfirmationFile::findOrFail($id);
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return response()->json(['message' => 'تم حذف الملف بنجاح']);
    }
}
