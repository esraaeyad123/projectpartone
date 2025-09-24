<?php
namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test;

use Illuminate\Http\Request;


class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->expectsJson()) {
        // جلب الاختبارات مع العلاقات
        return Test::with(['uncertainties', 'files'])->get();
    }

    // إذا كانت طلب عادي (غير AJAX)
    $tests = Test::with(['uncertainties', 'files'])->get();

    return view('testServicesAndCategory.index', compact('tests'));

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





public function store(Request $request)
{
    // Validate test data + uncertainty history
    $validated = $request->validate([
        'short_name'       => 'required|string|max:255',
        'service_group'    => 'nullable|string|max:255',
        'department'       => 'nullable|string|max:255',
        'generate_report'  => 'nullable|boolean',
        'description'      => 'nullable|string',
        'type'             => 'nullable|string|max:255',
        'activity_type'    => 'nullable|string|max:255',
        'date_added'       => 'nullable|date',
        'location'         => 'nullable|string|max:255',
        'test_method'      => 'nullable|string|max:255',
        'template_name'    => 'nullable|string|max:255',
        'template_type'    => 'nullable|string|max:255',
        'file_template'    => 'nullable|string|max:255',
        'report_designation' => 'nullable|string|max:255',
        'report_title'     => 'nullable|string|max:255',
        'built_in_template'=> 'nullable|string|max:255',
        'element'          => 'nullable|string|max:255',
        'uncertainty'      => 'nullable|string|max:255',
        'use_uncertainty'  => 'nullable|boolean',
        'unit_price'       => 'nullable|numeric|min:0',

        // Validate uncertainties
        'uncertainty_history'   => 'nullable|array',
        'uncertainty_history.*.value' => 'required_with:uncertainty_history|string|max:255',
        'uncertainty_history.*.date_recorded' => 'required_with:uncertainty_history|date',
    ]);

    // Create Test without uncertainty_history
    $testData = collect($validated)->except('uncertainty_history')->toArray();
    $test = Test::create($testData);

    // Save uncertainties if any
    if (!empty($validated['uncertainty_history'])) {
        foreach ($validated['uncertainty_history'] as $item) {
            $test->uncertainties()->create([
                'value' => $item['value'],
                'date_recorded' => $item['date_recorded'],
            ]);
        }
    }

    return response()->json([
        'id' => $test->id,
        'test' => $test->load('uncertainties'),
        'message' => 'Test created successfully',
    ]);
}



public function show($id)
{
    $test = Test::with('uncertainties')->findOrFail($id);
    return response()->json($test);
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
     public function update(Request $request, $id)
{
    // جلب الاختبار
    $test = Test::findOrFail($id);

    // التحقق من صحة البيانات القادمة من الفورم
    $validatedData = $request->validate([
        'short_name'       => 'required|string|max:255',
        'service_group'    => 'nullable|string|max:255',
        'department'       => 'nullable|string|max:255',
        'generate_report'  => 'nullable|boolean',
        'description'      => 'nullable|string',
        'type'             => 'nullable|string|max:255',
        'activity_type'    => 'nullable|string|max:255',
        'date_added'       => 'nullable|date',
        'location'         => 'nullable|string|max:255',
        'test_method'      => 'nullable|string|max:255',
        'template_name'    => 'nullable|string|max:255',
        'template_type'    => 'nullable|string|max:255',
        'file_template'    => 'nullable|string|max:255',
        'report_designation' => 'nullable|string|max:255',
        'report_title'     => 'nullable|string|max:255',
        'built_in_template' => 'nullable|string|max:255',
        'element'          => 'nullable|string|max:255',
        'uncertainty'      => 'nullable|string|max:255',
        'use_uncertainty'  => 'nullable|boolean',
        'unit_price'       => 'nullable|numeric',
        'show_to_customer' => 'nullable|boolean',
        'third_party'      => 'nullable|boolean',
        'active_status'    => 'nullable|boolean',
    ]);

    // تحديث الاختبار
    $test->update($validatedData);
     if ($request->has('uncertainty_history') && is_array($request->uncertainty_history)) {
        $test->uncertainties()->delete(); // حذف القديم

        foreach ($request->uncertainty_history as $item) {
            $test->uncertainties()->create([
                'value' => $item['value'],
                'date_recorded' => $item['date']
            ]);
        }
    }

    return response()->json([
        'status'  => 'success',
        'message' => 'Test updated successfully',
        'test'    => $test,
        'id'      => $test->id,
    ]);
}


    /**
     * Remove the specified resource from storage.
     */


    // حذف اختبار واحد
public function destroy(Test $test)
{
    $test->delete();
    return response()->json(['message' => 'Deleted']);
}

// حذف اختبارات متعددة
public function deleteMultiple(Request $request)
{
    $ids = $request->ids;
    if ($ids && is_array($ids)) {
        Test::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    return response()->json(['message' => 'No tests selected'], 400);
}

}
