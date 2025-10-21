<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationHeader;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectContact;
use App\Models\Employee;
use App\Models\QuotationCounter;
use Illuminate\Support\Facades\Mail; // If you want to send emails






class QuotationHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $quotations = QuotationHeader::all(); // مثال لجلب البيانات
    return view('quotation.index', compact('quotations'));
    }



public function getProjects()
{
    // تحميل كل العلاقات المحتملة
    $projects = Project::with(['customer', 'owner', 'consultant', 'contractor'])
        ->get()
        ->map(function ($project) {
            // اختيار العميل الفعلي حسب أول علاقة غير فارغة
            $actualCustomer = $project->customer
                ?? $project->owner
                ?? $project->consultant
                ?? $project->contractor;

            return [
                'id' => $project->id,
                'code' => $project->reference,
                'name' => $project->name,
                'arabic_name' => $project->arabic_name,
                'registration_date' => $project->registration_date,
                'region' => $project->region,
                'project_details' => $project->project_details,

                // العميل الحقيقي
                'customer_id' => $actualCustomer->id ?? null,
                'customer_name' => $actualCustomer->customer_name ?? null,

                // بقية الأطراف (للعرض فقط)
                'owner_name' => $project->owner->customer_name ?? null,
                'consultant_name' => $project->consultant->customer_name ?? null,
                'contractor_name' => $project->contractor->customer_name ?? null,
            ];
        });

    return response()->json($projects);
}



// Route: GET /quotation/contacts
public function getContacts(Request $request)
{
   $projectRef = $request->query('project');
    $customerId = $request->query('customer');

    // جلب المشروع مع التحقق من العميل إذا تم تمريره
    $project = Project::where('reference', $projectRef)
        ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
        ->first();

    if (!$project || !$project->customer) {
        return response()->json([
            'customer_contacts' => []
        ]);
    }

    // جهات اتصال العميل فقط
    $customerContacts = $project->customer->contacts()
        ->get(['id','name','position as title','email','mobile','phone'])
        ->map(function ($c) use ($project) {
            $c->city = $project->customer->city ?? null;
            return $c;
        });

    return response()->json([
        'customer_contacts' => $customerContacts
    ]);
}



 public function list()
    {
         $quotations = QuotationHeader::with(['customer.contacts', 'project', 'contact'])->get();

    $data = $quotations->map(function($q) {
        $contact = $q->contact; // قد يكون null
        $customerContacts = $q->customer ? $q->customer->contacts : collect();

        // نختار أول جهة اتصال للعرض في الجدول، أو يمكن تعديل حسب الحاجة
        $firstCustomerContact = $customerContacts->first();

        return [
            'id'              => $q->id,
            'quote_category'  => $q->quote_category,
            'quote_no'        => $q->quote_no,
            'rev'             => $q->rev,
            'quote_date'      => $q->quote_date,
            'project_code'    => optional($q->project)->reference,
            'legacy_no'       => $q->legacy_no,
            'legacy_date'     => $q->legacy_date,
            'customer_name'   => optional($q->customer)->customer_name,
            'project_name'    => optional($q->project)->name,
            'project_details' => optional($q->project)->project_details,
            'subject'         => $q->subject,
            'contact_from'    => $q->contact_from,
            'inquiry'         => $q->inquiry,
            'contact'         => optional($firstCustomerContact)->name,
            'to'              => optional($firstCustomerContact)->email,
            'attn_to'         => optional($firstCustomerContact)->name,
            'attn_pos'        => optional($firstCustomerContact)->position,
            'contact_email'   => optional($firstCustomerContact)->email,
            'contact_mobile'  => optional($firstCustomerContact)->mobile,
            'contact_phone'   => optional($firstCustomerContact)->phone,
            'discount'        => $q->discount,
            'vat'             => $q->vat,
            'validity'        => $q->validity_days,
            'currency'        => $q->currency,
            'payment_terms'   => $q->payment_terms,
            'method'          => $q->method,
            'remarks'         => $q->remarks,
            'quote_file'      => $q->quote_file,
            'file_status'     => $q->file_status,
            'declined'        => $q->declined,
            'declined_message'=> $q->declined_message,
            'isNew'           => true,
            'isSent'          => false,
            'isActive'        => false,
            'isApproved'      => false,
            'isRejected'      => false,
        ];
    });

    return response()->json($data);
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */



// توليد رقم عرض السعر (يقبل أي فئة)
private function generateQuotationNumber(string $category): string
{
    $prefix = 'AAM';
    $year = date('y');

    // تحويل الفئة إلى اختصار تلقائي (أول حرف من كل كلمة، مع إزالة الأحرف غير الأبجدية)
    $words = preg_split('/\s+/', $category); // تقسيم على المسافات
    $categoryPrefix = '';
    foreach ($words as $word) {
        $firstChar = preg_replace('/[^A-Za-z0-9]/', '', $word); // إزالة الأحرف الغريبة
        if (!empty($firstChar)) {
            $categoryPrefix .= strtoupper($firstChar[0]);
        }
    }

    if (empty($categoryPrefix)) {
        $categoryPrefix = 'XX'; // افتراضي إذا الفئة فارغة أو تحتوي أحرف غير صالحة
    }

    // التعامل مع العداد
    $counter = QuotationCounter::firstOrCreate(
        ['category' => $category],
        ['last_number' => 0]
    );
    $counter->increment('last_number');

    $formatted = str_pad($counter->last_number, 5, '0', STR_PAD_LEFT);

    return "$prefix-$categoryPrefix-Q-$year-$formatted";
}



// حفظ أو إنشاء عرض سعر
public function store(Request $request)
{
    $validated = $request->validate([
        'id'               => 'nullable|integer|exists:quotation_headers,id',
        'customer_id'      => 'required|integer|exists:customers,id',
        'project_id'       => 'required|integer|exists:projects,id',
        'contact_id'       => 'required|integer|exists:contacts,id',
        'quote_category'   => 'required|string|max:255',
        'rev'              => 'nullable|string|max:50',
        'quote_date'       => 'nullable|date',
        'legacy_no'        => 'nullable|string|max:255',
        'legacy_date'      => 'nullable|date',
        'subject'          => 'nullable|string|max:255',
        'currency'         => 'nullable|string|max:10',
        'discount'         => 'nullable|numeric',
        'vat'              => 'nullable|numeric',
        'validity_days'    => 'nullable|integer',
        'payment_terms'    => 'nullable|string|max:255',
        'method'           => 'nullable|string|max:255',
        'remarks'          => 'nullable|string',
        'quote_file'       => 'nullable|string|max:255',
        'file_status'      => 'nullable|string|max:50',
        'declined'         => 'nullable|boolean',
        'declined_message' => 'nullable|string',
        'total_lines'      => 'nullable|numeric',
        'discount_amount'  => 'nullable|numeric',
        'tax_amount'       => 'nullable|numeric',
        'grand_total'      => 'nullable|numeric',
        'inquiry'          => 'nullable|string|max:255',
        'contact_from'     => 'nullable|string|max:255',
        'attn_to'          => 'nullable|string|max:255',
        'attn_pos'         => 'nullable|string|max:255',
        'contact_email'    => 'nullable|string|max:255',
        'contact_mobile'   => 'nullable|string|max:255',
        'use_alt_form'     => 'nullable|boolean',
        'overall_status'   => 'nullable|string|max:255',
        'last_confirmation'=> 'nullable|date',
        'last_confirmed'   => 'nullable|date',
        'project_details'  => 'nullable|string',
    ]);

    if (!empty($validated['id'])) {
        // تحديث عرض سعر موجود
        $quotation = QuotationHeader::findOrFail($validated['id']);
        $quotation->update($validated);
        $message = 'Quotation updated successfully!';
    } else {
        // إنشاء عرض سعر جديد
        $validated['quote_no'] = $this->generateQuotationNumber($validated['quote_category']);
        $quotation = QuotationHeader::create($validated);
        $message = 'Quotation created successfully!';
    }

    return response()->json([
        'success'      => true,
        'quotation_id' => $quotation->id,
        'quote_no'     => $quotation->quote_no,
        'message'      => $message,
    ]);
}


    /**
     * Display the specified resource.
     */
   public function show($id)
{
    $quotation = QuotationHeader::with(['customer','project','contact'])->findOrFail($id);

    return response()->json([
        'header' => [
            'id' => $quotation->id,
            'customer_id' => $quotation->customer_id,
            'project_id' => $quotation->project_id,
            'contact_id' => $quotation->contact_id,
            'quote_no' => $quotation->quote_no,
            'rev' => $quotation->rev,
            'quote_date' => $quotation->quote_date,
            'quote_category' => $quotation->quote_category,
            'project_code' => $quotation->project->reference ?? '',
            'customer_name' => $quotation->customer->customer_name ?? '',
            'project_name' => $quotation->project->name ?? '',
            'legacy_date' => $quotation->legacy_date,
            'contact_from' => $quotation->contact_from,
            'inquiry' => $quotation->inquiry,
            'legacy_no' => $quotation->legacy_no,
            'subject' => $quotation->subject,
            'contact_person' => $quotation->contact->name ?? '',
            'contact_to' => ($quotation->customer->city ?? '') . ' - ' . ($quotation->contact->email ?? ''),
            'attn_to' => $quotation->contact->name ?? '',
            'attn_pos' => $quotation->contact->position ?? '',
            'contact_email' => $quotation->contact->email ?? '',
            'contact_mobile' => $quotation->contact->mobile ?? '',
            'currency' => $quotation->currency,
            'discount' => $quotation->discount,
            'vat' => $quotation->vat,
            'validity_days' => $quotation->validity_days,
            'payment_terms' => $quotation->payment_terms,
            'method' => $quotation->method,
            'use_alt_form' => $quotation->use_alt_form,
            'remarks' => $quotation->remarks,
            'quote_file' => $quotation->quote_file,
            'file_status' => $quotation->file_status,
            'declined' => $quotation->declined,
            'declined_message' => $quotation->declined_message,
            'total_lines' => $quotation->total_lines,
            'discount_amount' => $quotation->discount_amount,
            'tax_amount' => $quotation->tax_amount,
            'grand_total' => $quotation->grand_total,
            'overall_status' => $quotation->overall_status,
            'last_confirmation' => $quotation->last_confirmation,
            'last_confirmed' => $quotation->last_confirmed,
            'project_details' => $quotation->project_details,
            'isNew' => !$quotation->quote_file,
            'isSent' => $quotation->file_status === 'sent',
            'isActive' => $quotation->overall_status === 'active',
            'isApproved' => $quotation->overall_status === 'approved',
            'isRejected' => $quotation->overall_status === 'rejected',
        ],
        'lines' => $quotation->lines ?? []
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    // QuotationHeaderController.php
public function update(Request $request, $id)
{
    $quotation = QuotationHeader::findOrFail($id);

    $validated = $request->validate([
       'customer_id'        => 'required|exists:customers,id',
    'project_id'         => 'required|exists:projects,id',
    'contact_id'         => 'nullable|exists:contacts,id',
    'quote_category'     => 'nullable|string|max:255',
    'quote_no'           => 'required|string|max:255',
    'rev'                => 'nullable|string|max:50',
    'quote_date'         => 'nullable|date',
    'legacy_no'          => 'nullable|string|max:255',
    'legacy_date'        => 'nullable|date',
    'subject'            => 'nullable|string|max:255',
    'currency'           => 'nullable|string|max:10',
    'discount'           => 'nullable|numeric',
    'vat'                => 'nullable|numeric',
    'validity_days'      => 'nullable|integer',
    'payment_terms'      => 'nullable|string|max:255',
    'method'             => 'nullable|string|max:255',
    'remarks'            => 'nullable|string',
    'inquiry'            => 'nullable|string|max:255',
    'contact_from'       => 'nullable|string|max:255',
    'use_alt_form'       => 'nullable|boolean',
    'overall_status'     => 'nullable|string|max:255',
    'last_confirmation'  => 'nullable|date',
    'last_confirmed'     => 'nullable|date',
    'project_details'    => 'nullable|string',

    // ✅ إضافات Right Sidebar
    'total_lines'        => 'nullable|numeric',
    'discount_amount'    => 'nullable|numeric',
    'tax_amount'         => 'nullable|numeric',
    'grand_total'        => 'nullable|numeric',
    ]);

    $quotation->update($validated);

    return response()->json([
        'success' => true,
        'quotation_id' => $quotation->id,
        'message' => 'Quotation updated successfully!'
    ]);
}



   public function deleteSelected(Request $request)
{
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return response()->json(['message' => 'لم يتم تحديد أي عروض أسعار للحذف.'], 400);
    }

    try {
        QuotationHeader::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'تم حذف عروض الأسعار بنجاح.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'فشل حذف البيانات: ' . $e->getMessage()], 500);
    }
}

public function generatePdf($id)
{
    $quotation = QuotationHeader::findOrFail($id);

    // تحديث الحالة في قاعدة البيانات
    $quotation->file_status = 'PDF Generated';
    $quotation->save();

    // يمكن هنا توليد PDF فعليًا إذا أردت

    return response()->json([
        'message' => 'PDF generated successfully',
        'file_status' => $quotation->file_status
    ]);
}


public function getEmployees()
{
    // جلب كل الموظفين من قاعدة البيانات
    $employees = Employee::select('initials', 'full_name', 'title')->get();

    return response()->json($employees);
}





public function edit($id)
{
    // 🔹 جلب عرض السعر مع العلاقات الثلاث
    $quotation = QuotationHeader::with(['customer', 'project', 'contact'])->findOrFail($id);

    return response()->json([
       'id' => $quotation->id,
    'quote_no' => $quotation->quote_no,
    'quote_category' => $quotation->quote_category,
    'rev' => $quotation->rev,
    'quote_date' => $quotation->quote_date,
    'legacy_no' => $quotation->legacy_no,
    'legacy_date' => $quotation->legacy_date,
    'subject' => $quotation->subject,
    'currency' => $quotation->currency,
    'discount' => $quotation->discount,
    'vat' => $quotation->vat,
    'validity_days' => $quotation->validity_days,
    'payment_terms' => $quotation->payment_terms,
    'method' => $quotation->method,
    'remarks' => $quotation->remarks,
    'quote_file' => $quotation->quote_file,
    'file_status' => $quotation->file_status,
    'declined' => $quotation->declined,
    'declined_message' => $quotation->declined_message,
    'use_alt_form' => $quotation->use_alt_form,
    'inquiry' => $quotation->inquiry,
    'project_details' => $quotation->project_details,
    'contact_from' => $quotation->contact_from,
    'attn_to' => $quotation->contact?->name,
    'attn_pos' => $quotation->contact?->position,
    'contact_email' => $quotation->contact?->email,
    'contact_mobile' => $quotation->contact?->mobile,

    // Financials
    'total_lines' => $quotation->total_lines,
    'discount_amount' => $quotation->discount_amount,
    'tax_amount' => $quotation->tax_amount,
    'grand_total' => $quotation->grand_total,

    // Quote Status
    'overall_status' => $quotation->overall_status,
    'last_confirmation' => $quotation->last_confirmation,
    'last_confirmed' => $quotation->last_confirmed,

    // Relationships
    'customer_name' => $quotation->customer?->customer_name,
    'customer_id' => $quotation->customer?->id,
    'project_name' => $quotation->project?->name,
    'project_code' => $quotation->project?->code,
    'project_id' => $quotation->project?->id,
    'contact_name' => $quotation->contact?->name,
    'contact_id' => $quotation->contact?->id,

    ]);
}

public function revise(QuotationHeader $quotation)
{
    // مثال: زيادة رقم المراجعة
    $currentRev = intval($quotation->rev ?? 0);
    $newRev = $currentRev + 1;
    $quotation->rev = $newRev;
    $quotation->save();

    return response()->json(['success' => true, 'new_rev' => $newRev]);
}

public function sendToCustomer(Request $request)
    {
        $validated = $request->validate([
            'quotation_ids' => 'required|array|min:1',
            'quotation_ids.*' => 'exists:quotation_headers,id',
        ]);

        $quotationIds = $validated['quotation_ids'];
        $sentCount = 0;

        foreach ($quotationIds as $id) {
            $quotation = QuotationHeader::find($id);

            if (!$quotation) continue;

            // هنا ضع عملية الإرسال (مثلاً إرسال إيميل أو تسجيل في النظام)
           // Mail::to($quotation->customer_email)->send(new QuotationMail($quotation));

            // مؤقتًا فقط نرفع العدد
            $sentCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "$sentCount اقتباس/اقتباسات تم إرسالها بنجاح."
        ]);
    }


public function sendForApproval(Request $request)
{
    $validated = $request->validate([
        'quotation_ids' => 'required|array|min:1',
        'quotation_ids.*' => 'exists:quotation_headers,id',
    ]);

    $quotationIds = $validated['quotation_ids'];
    $processedCount = 0;

    foreach ($quotationIds as $id) {
        $quotation = QuotationHeader::find($id);
        if (!$quotation) continue;

        // هنا ضع أي عملية تحتاجها لإرسال الاقتباس للموافقة
        // مثل تغيير حالة: $quotation->status = 'pending_approval';
        $quotation->overall_status = 'pending_approval';
        $quotation->save();

        $processedCount++;
    }

    return response()->json([
        'success' => true,
        'message' => "$processedCount اقتباس/اقتباسات تم إرسالها للموافقة."
    ]);
}

public function confirm(Request $request)
{
    $validated = $request->validate([
        'quotation_ids' => 'required|array|min:1',
        'quotation_ids.*' => 'exists:quotation_headers,id',
    ]);

    $quotationIds = $validated['quotation_ids'];
    $confirmedCount = 0;

    foreach ($quotationIds as $id) {
        $quotation = QuotationHeader::find($id);
        if (!$quotation) continue;

        // تحديث حالة الاقتباس إلى "confirmed"
        $quotation->overall_status = 'confirmed';
        $quotation->save();

        $confirmedCount++;
    }

    return response()->json([
        'success' => true,
        'message' => "$confirmedCount اقتباس/اقتباسات تم تأكيدها."
    ]);
}



}
