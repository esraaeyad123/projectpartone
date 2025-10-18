<?php

namespace App\Http\Controllers\Sample;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Sample;
use App\Models\Order;
use App\Models\CrushingTest;
use Illuminate\Support\Facades\DB;

class SampleController extends Controller
{
    // عرض صفحة العينة
    public function index()
    {
        $customers = Customer::with('contacts')->get();
        $projects = Project::all();
        return view('sample.index', compact('customers', 'projects'));
    }

    // جلب Dropdowns
    public function getDropdowns()
    {
        $labs = DB::table('labs')->select('id','location_name','department')->get();
        $sampleTypes = DB::table('sample_types')->select('id','name')->get();
        $testMethods = DB::table('test_methods')->select('id','name')->get();
        $testLocations = DB::table('test_locations')->select('id','name')->get();

        return response()->json([
            'labs' => $labs,
            'sampleTypes' => $sampleTypes,
            'testMethods' => $testMethods,
            'testLocations' => $testLocations
        ]);
    }

    // جلب Projects
 public function getProjects() {
    $projects = Project::with('customer')->get()->map(function($p) {
        return [
            'id' => $p->id,
            'reference' => $p->reference,
            'name' => $p->name,
            'arabic_name' => $p->arabic_name,
            'customer_name' => $p->customer->customer_name ?? ''
        ];
    });
    return response()->json($projects);
}

    // حفظ العينة والاختبارات
   public function store2(Request $request)
{
    // ✅ التحقق من أن المشروع محدد
    if (!$request->project_id) {
        return response()->json(['error' => 'Project is required'], 422);
    }

    // ✅ البحث عن آخر طلب للمشروع أو إنشاء واحد جديد
    $order = \App\Models\Order::where('project_id', $request->project_id)
        ->latest('id')
        ->first();

    if (!$order) {
        $order = \App\Models\Order::create([
            'project_id' => $request->project_id,
            'order_no' => 'ORD-' . str_pad((\App\Models\Order::max('id') + 1), 4, '0', STR_PAD_LEFT),
            'order_date' => now(),
        ]);
    }

    // ✅ إنشاء العينة مع ربط الطلب تلقائيًا
    $sample = \App\Models\Sample::create([
        'order_id' => $order->id,
        'project_id' => $request->project_id,
        'sample_type_id' => $request->sample_type_id,
        'lab_id' => $request->lab_id,
        'sample_source' => $request->sample_source,
        'sample_description' => $request->sample_description,
        'rfi_wir' => $request->rfi_wir,
        'structure_ref' => $request->structure_ref,
        'sampling_method' => $request->sampling_method,
        'received_at' => $request->received_at,
        'total_received' => $request->total_received,
        'spec_prop_by' => $request->spec_prop_by,
        'sampled_at' => $request->sampled_at,
        'received_by' => $request->received_by,
        'sampled_by' => $request->sampled_by,
        'casted_at' => $request->casted_at,
        'samp_brought_by' => $request->samp_brought_by,
        'comp_equipment' => $request->comp_equipment,
        'witness' => $request->witness,
        'mtd_of_comp' => $request->mtd_of_comp,
        'cement_content' => $request->cement_content,
        'notes' => $request->notes,
        'cement_type' => $request->cement_type,
        'nominal_size' => $request->nominal_size,
        'class' => $request->class,
    ]);

    // ✅ إضافة اختبارات الـ Crushing (إن وجدت)
    if ($request->tests && is_array($request->tests)) {
        foreach ($request->tests as $test) {
            \App\Models\CrushingTest::create([
                'sample_id' => $sample->id,
                'test_method_id' => $test['test_method_id'] ?? null,
                'test_location_id' => $test['test_location_id'] ?? null,
                'r_age' => $test['r_age'] ?? null,
                'a_age' => $test['a_age'] ?? null,
                'price' => $test['price'] ?? null,
                'qty' => $test['qty'] ?? null,
                'scheduled_at' => $test['scheduled_at'] ?? null,
                'rem' => $test['rem'] ?? null,
                'report_no' => $test['report_no'] ?? null,
                'rev' => $test['rev'] ?? null,
                'items' => $test['items'] ?? null,
            ]);
        }
    }

    // ✅ إرجاع الرد
    return response()->json([
        'message' => 'Sample and tests saved successfully',
        'sample_no' => $sample->sample_no,
        'order_no' => $order->order_no,
        'sample_id' => $sample->id,
    ]);
}


public function store(Request $request)
{
    try {
        // هنا كود الحفظ
        $data = $request->all();

        // مثال على الحفظ:
        $sample = Sample::create($data);

        return response()->json([
            'success' => true,
            'sample_no' => $sample->sample_no,
            'order_no' => $sample->order_no
        ]);

    } catch (\Exception $e) {
        // لو صار أي خطأ نرجع رسالة واضحة
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}



}
