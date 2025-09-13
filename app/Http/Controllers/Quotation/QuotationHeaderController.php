<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationHeader;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectContact;


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
    // جلب المشاريع مع علاقة العميل
    $projects = Project::with('customer')->get()->map(function($project) {
        return [
                'id' => $project->id,
        'code' => $project->reference,
        'name' => $project->name,
        'customer' => $project->customer->customer_name ?? '',
        'customer_id' => $project->customer->id ?? null, // 🔹 أضف هذا

        ];
    });

    return response()->json($projects);

}

// Route: GET /quotation/contacts
public function getContacts(Request $request)
{
    $projectRef = $request->query('project');
    $customerId = $request->query('customer');

    $project = Project::where('reference', $projectRef)
        ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
        ->first();

    if (!$project) {
        return response()->json([
            'project_contacts' => [],
            'customer_contacts' => []
        ]);
    }

    // جهات اتصال المشروع
    $projectContacts = $project->contacts()
        ->get(['id','name','position as title','email','mobile','phone'])
        ->map(function ($c) use ($project) {
            $c->city = $project->customer->city ?? null;
            return $c;
        });

    // جهات اتصال العميل
    $customerContacts = $project->customer->contacts()
        ->get(['id','name','position as title','email','mobile','phone'])
        ->map(function ($c) use ($project) {
            $c->city = $project->customer->city ?? null;
            return $c;
        });

    return response()->json([
        'project_contacts' => $projectContacts,
        'customer_contacts' => $customerContacts
    ]);
}


      public function list(Request $request)
{
    $quotations = QuotationHeader::with(['customer', 'project', 'contact'])->get()->map(function($q) {
        return [
            'id' => $q->id,
            'customer_id' => $q->customer_id,
            'project_id' => $q->project_id,
            'contact_id' => $q->contact_id,
            'quote_no' => $q->quote_no,
            'rev' => $q->rev,
            'quote_date' => $q->quote_date,
            'quote_category' => $q->quote_category,
            'project_code' => $q->project->reference ?? '',
            'customer_name' => $q->customer->customer_name ?? '',
            'project_name' => $q->project->name ?? '',
            'legacy_date' => $q->legacy_date,
            'legacy_no' => $q->legacy_no,
            'subject' => $q->subject,
            'contact_from' => $q->contact_from,
            'inquiry' => $q->inquiry,
            'contact' => $q->contact->name ?? '',
            'to' => ($q->customer->city ?? '') . ' - ' . ($q->contact->email ?? ''),
            'attn_to' => $q->contact->name ?? '',
            'attn_pos' => $q->contact->position ?? '',
            'contact_email' => $q->contact->email ?? '',
            'contact_mobile' => $q->contact->mobile ?? '',
            'currency' => $q->currency,
            'discount' => $q->discount,
            'vat' => $q->vat,
            'validity' => $q->validity_days,
            'payment_terms' => $q->payment_terms,
            'method' => $q->method,
            'use_alt_form' => $q->use_alt_form,
            'remarks' => $q->remarks,
            'quote_file' => $q->quote_file,
            'file_status' => $q->file_status,
            'declined' => $q->declined,
            'declined_message' => $q->declined_message,
            'total_lines' => $q->total_lines,
            'discount_amount' => $q->discount_amount,
            'tax_amount' => $q->tax_amount,
            'grand_total' => $q->grand_total,
            'overall_status' => $q->overall_status,
            'last_confirmation' => $q->last_confirmation,
            'last_confirmed' => $q->last_confirmed,
            'project_details' => $q->project_details,
            'isNew' => !$q->quote_file, // مثال: إذا لم يكن هناك ملف، اعتبره جديد
            'isSent' => $q->file_status === 'sent', // مثال: حالة الإرسال
            'isActive' => $q->overall_status === 'active',
            'isApproved' => $q->overall_status === 'approved',
            'isRejected' => $q->overall_status === 'rejected',
        ];
    });

    return response()->json($quotations);
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
   public function saveHeader(Request $request)
    {
       $validated = $request->validate([
        'id'             => 'nullable|integer|exists:quotation_headers,id',
        'customer_id'    => 'required|integer|exists:customers,id',
        'project_id'     => 'required|integer|exists:projects,id',
        'contact_id'     => 'required|integer|exists:contacts,id',
        'quote_category' => 'required|string|max:255',
        'quote_no'       => 'required|string|max:255',
        'rev'            => 'nullable|string|max:50',
        'quote_date'     => 'nullable|date',
        'legacy_no'      => 'nullable|string|max:255',
        'legacy_date'    => 'nullable|date',
        'subject'        => 'nullable|string|max:255',
        'currency'       => 'nullable|string|max:10',
        'discount'       => 'nullable|numeric',
        'vat'            => 'nullable|numeric',
        'validity_days'  => 'nullable|integer',
        'payment_terms'  => 'nullable|string|max:255',
        'method'         => 'nullable|string|max:255',
        'remarks'        => 'nullable|string',
        'quote_file'     => 'nullable|string|max:255',
        'file_status'    => 'nullable|string|max:50',
        'declined'       => 'nullable|boolean',
        'declined_message'=> 'nullable|string',
        'total_lines'    => 'nullable|numeric',
        'discount_amount'=> 'nullable|numeric',
        'tax_amount'     => 'nullable|numeric',
        'grand_total'    => 'nullable|numeric',
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

    // إذا كان هناك id، قم بالتحديث، وإلا قم بالإنشاء
        $quotation = QuotationHeader::create($validated);


    return response()->json([
       'success'       => true,
        'quotation_id' => $quotation->id,
        'message'      => 'Quotation saved successfully!'
    ]);
 }

    /**
     * Display the specified resource.
     */
public function show($id)
{
    $quotation = QuotationHeader::with(['customer','project','contact'])->findOrFail($id);

    return response()->json([
            'id' => $quotation->id,
        'customer_id' => $quotation->customer_id,
        'project_id' => $quotation->project_id,
        'contact_id' => $quotation->contact_id,
        'quote_no' => $quotation->quote_no,
        'rev' => $quotation->rev,
        'quote_date' => $quotation->quote_date,
        'quote_category' => $quotation->quote_category,
        'project_code' => $quotation->project->reference  ?? '',
        'customer_name' => $quotation->customer->customer_name ?? '',
        'projectName' => $quotation->project->name ?? '',
        'legacy_date' => $quotation->legacy_date,
        'contact_from' => $quotation->contact_from,
        'inquiry' => $quotation->inquiry,
        'legacy_no' => $quotation->legacy_no,
        'subject' => $quotation->subject,
        'contact_person' => $quotation->contact->name ?? '',
        'contact_id' => $quotation->contact_id,
        'contact_to' => ($quotation->customer->city ?? '') . ' - ' . ($quotation->contact->email ?? ''),
        'attn_to' => $quotation->contact->name ?? '',
        'attn_pos' => $quotation->contact->position ?? '',
        'contact_email' => $quotation->contact->email,
        'contact_mobile' => $quotation->contact->mobile,
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
    ]);
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    // QuotationHeaderController.php
public function update(Request $request, $id)
{
    $quotation = QuotationHeader::findOrFail($id);

    $validated = $request->validate([
        'customer_id'    => 'required|integer|exists:customers,id',
        'project_id'     => 'required|integer|exists:projects,id',
        'contact_id'     => 'required|integer|exists:contacts,id',
        'quote_category' => 'required|string|max:255',
        'quote_no'       => 'required|string|max:255',
        'rev'            => 'nullable|string|max:50',
        'quote_date'     => 'nullable|date',
        'legacy_no'      => 'nullable|string|max:255',
        'legacy_date'    => 'nullable|date',
        'subject'        => 'nullable|string|max:255',
        'currency'       => 'nullable|string|max:10',
        'discount'       => 'nullable|numeric',
        'vat'            => 'nullable|numeric',
        'validity_days'  => 'nullable|integer',
        'payment_terms'  => 'nullable|string|max:255',
        'method'         => 'nullable|string|max:255',
        'remarks'        => 'nullable|string',
        'quote_file'     => 'nullable|string|max:255',
        'file_status'    => 'nullable|string|max:50',
        'declined'       => 'nullable|boolean',
        'declined_message'=> 'nullable|string',
        'total_lines'    => 'nullable|numeric',
        'discount_amount'=> 'nullable|numeric',
        'tax_amount'     => 'nullable|numeric',
        'grand_total'    => 'nullable|numeric',
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

}
