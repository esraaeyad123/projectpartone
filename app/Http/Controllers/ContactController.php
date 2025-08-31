<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 📌 عرض جميع الاتصالات لعميل معين
    public function index(Customer $customer)
    {
        return response()->json([
            'data' => $customer->contacts
        ]);
    }

    // 📌 حفظ اتصال جديد
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:20',
            'mobile'     => 'nullable|string|max:20',
            'position'   => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $validated['customer_id'] = $customer->id;

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => '✅ Contact created successfully',
            'contact' => $contact
        ]);
    }


    public function show($id)
{
    $contact = Contact::find($id);
    if (!$contact) {
        return response()->json(['message' => 'Contact not found'], 404);
    }
    return response()->json(['contact' => $contact]);
}

    // 📌 تعديل اتصال موجود
   public function update(Request $request, Customer $customer, Contact $contact)
{
    if ($contact->customer_id !== $customer->id) {
        return response()->json(['message' => 'Contact not found'], 404);
    }

    $validated = $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'nullable|email',
        'phone'      => 'nullable|string|max:20',
        'mobile'     => 'nullable|string|max:20',
        'position'   => 'nullable|string|max:255',
        'is_primary' => 'boolean',
    ]);

    $contact->update($validated);

    return response()->json([
        'success' => true,
        'message' => '✅ Contact updated successfully',
        'contact' => $contact
    ]);
}


    // 📌 حذف اتصال
   public function deleteMultiple(Request $request)
{
    $ids = $request->input('ids', []);
    if (empty($ids)) {
        return response()->json(['success' => false, 'message' => 'لا يوجد جهات اتصال محددة']);
    }

    Contact::whereIn('id', $ids)->delete();

    return response()->json([
        'success' => true,
        'message' => '✅ تم حذف جهات الاتصال بنجاح'
    ]);
}

}
