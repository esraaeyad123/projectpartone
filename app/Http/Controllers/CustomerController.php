<?php

namespace App\Http\Controllers;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{

$customers = Customer::all();
    return view('customers.index', compact('customers'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
{

}

public function getCustomersData(Request $request)
{
    $customers = Customer::all(); // أو استخدم paginate() إذا أحببت

    return response()->json([
        'data' => $customers
    ]);
}



public function store(Request $request)
{
    $validated = $request->validate([
        'id'                  => 'nullable|exists:customers,id',
        'customer_name'       => 'required|string|max:255',
        'arabic_name'         => 'nullable|string|max:255',
        'customer_legal_name' => 'nullable|string|max:255',
        'customer_type'       => 'nullable|string',
        'potential'           => 'nullable|boolean',
        'legacy_acc_no'       => 'nullable|string|max:255',
        'date_registered'     => 'nullable|date',
        'phone'               => 'nullable|string|max:50',
        'country'             => 'nullable|string|max:255',
        'arabic_location'     => 'nullable|string|max:255',
        'city'                => 'nullable|string|max:255',
        'district'            => 'nullable|string|max:255',
        'street'              => 'nullable|string|max:255',
        'post_code'           => 'nullable|string|max:255',
        'address_block'       => 'nullable|string|max:255',
        'po_box'              => 'nullable|string|max:255',
        'building_no'         => 'nullable|string|max:255',
        'payment_terms'       => 'nullable|string|max:255',
        'discount'            => 'nullable|numeric',
        'cash'                => 'nullable|boolean',
        'credit_limit'        => 'nullable|numeric',
        'vat_profile'         => 'nullable|string|max:255',
        'trn_tin'             => 'nullable|string|max:255',
        'registration_no'     => 'nullable|string|max:255',
        'restrict_deliveries' => 'nullable|boolean',
        'restrict_orders'     => 'nullable|boolean',
        'restrict_quotations' => 'nullable|boolean',
    ]);

    // إذا هناك id موجود (تعديل)، استخدمه، وإلا إنشاء جديد
    $customer = Customer::updateOrCreate(
        ['id' => $request->id],
        array_merge($validated, [
            'potential' => $request->potential ?? false,
            'cash'      => $request->cash ?? false,
            'discount'  => $request->discount ?? 0,
            'credit_limit' => $request->credit_limit ?? 0,
            'vat_profile'  => $request->vat_profile ?? 'Standard VAT',
            'restrict_deliveries' => $request->restrict_deliveries ?? false,
            'restrict_orders'     => $request->restrict_orders ?? false,
            'restrict_quotations' => $request->restrict_quotations ?? false,
        ])
    );


    return response()->json([
        'success' => true,
        'customer' => $customer
    ]);
}

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
 public function show($id)
{
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json(['message' => '❌ لم يتم العثور على بيانات العميل'], 404);
    }

    return response()->json($customer);
}



    /**
     * Show the form for editing the specified resource.
     */


    public function edit($id)
{
    $customer = Customer::with('contacts')->find($id);

    if (!$customer) {
        return response()->json([
            'status' => 'error',
            'message' => 'Customer not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'customer' => $customer
    ]);
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

public function bulkDelete(Request $request)
{
    $ids = $request->ids; // المصفوفة المرسلة من الجافاسكربت
    Customer::whereIn('id', $ids)->delete(); // حذف من قاعدة البيانات

    return response()->json(['success' => true, 'message' => 'Customers deleted successfully!']);
}



public function exportSelected(Request $request)
{

    
    $data = $request->all();
    $all = $data['all'] ?? false;
    $ids = $data['ids'] ?? [];


    return Excel::download(new CustomersExport($all, $ids), 'customers.xlsx');
}



}
