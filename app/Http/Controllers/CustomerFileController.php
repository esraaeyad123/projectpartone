<?php

namespace App\Http\Controllers;

use App\Models\CustomerFile;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerFileController extends Controller
{
    // عرض صفحة ملفات العميل
    public function index($customerId)
    {
        $customer = Customer::with('files')->find($customerId);

        if (!$customer) {
            abort(404, 'العميل غير موجود');
        }

        return view('customers.customer-files', compact('customer'));
    }

    // رفع ملف جديد
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('customer_files/' . $request->customer_id, 'public');

        $customerFile = CustomerFile::create([
            'customer_id' => $request->customer_id,
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'path' => $path,
            'size' => $file->getSize(),
        ]);

        return response()->json($customerFile);
    }

    // حذف ملف
    public function destroy($id)
    {
        $file = CustomerFile::findOrFail($id);
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'تم حذف الملف بنجاح']);
    }

    // تنزيل ملف
    public function download($id)
    {
        $file = CustomerFile::findOrFail($id);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($file->path, $file->name);
    }

    // جلب ملفات العميل كـ JSON
    public function filesJson(Customer $customer)
    {
        $files = $customer->files()->get()->map(function($file) {
            return [
                'id' => $file->id,
                'name' => $file->name, // تأكد من اسم العمود الصحيح في الجدول
                'size' => $file->size,
                'created_at' => $file->created_at->format('Y-m-d'),
            ];
        });

        return response()->json($files);
    }

    public function view($id)
{
    $file = CustomerFile::findOrFail($id);

    // تحقق من وجود الملف في التخزين
    if (!Storage::disk('public')->exists($file->path)) {
        abort(404, 'File not found');
    }

    // إذا كان الملف صورة أو PDF يمكن فتحه في المتصفح
    $mime = Storage::disk('public')->mimeType($file->path);
    return response()->file(storage_path("app/public/{$file->path}"), [
        'Content-Type' => $mime
    ]);
}


    // عرض ملف (اختياري)
    public function show(CustomerFile $customerFile)
    {
        //
    }

    public function edit(CustomerFile $customerFile) { }
    public function update(Request $request, CustomerFile $customerFile) { }
}
