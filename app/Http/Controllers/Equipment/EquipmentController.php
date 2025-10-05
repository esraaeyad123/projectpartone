<?php

namespace App\Http\Controllers\Equipment;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\UncertaintyRecord;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
{
    if ($request->expectsJson()) {
        // جلب كل المعدات مع المصنع إذا عندك علاقة Manufacturer
        return Equipment::with('equipmentUncertainties')->get();
    }

    // العرض العادي للصفحة
 $projects = Project::all(); // رجع كل المشاريع
    return view('equipments.index', compact('projects'));

}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $projects = Project::all(); // رجع كل المشاريع
    return view('equipments.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    // ✅ Validate أول 5 حقول + الهيستوري
    $validated = $request->validate([
        'alternative_id'   => 'nullable|string|max:255',
        'legacy_id'        => 'nullable|string|max:255',
        'description'      => 'required|string|max:255',
        'serial_number'    => 'nullable|string|max:255',
        'asset_tag'        => 'nullable|string|max:255',

        // ✅ فقط uncertainty history
        'uncertainty_history'   => 'nullable|array',
     'uncertainty_history.*.value' => 'required_with:uncertainty_history|string|max:255',
     'uncertainty_history.*.date_recorded' => 'required_with:uncertainty_history|date',

    ]);

    // ✅ باقي الحقول ناخذها مباشرة من $request
    $equipmentData = $request->only([
        'alternative_id', 'legacy_id', 'description', 'serial_number', 'asset_tag',
        'size', 'type', 'make', 'model', 'tolerance_basis', 'tolerance',
        'range_capacity', 'range_unit', 'resolution', 'resolution_unit',
        'traceability', 'display_type', 'manufacturer', 'department',
        'custodian', 'location', 'uncertainty', 'uncertainty_unit', 'io',
        'master_equipment', 'equipment_status', 'project_id'
    ]);

    // ✅ إنشاء الجهاز
    $equipment = Equipment::create($equipmentData);

    // ✅ حفظ الـ uncertainty_records إذا موجودة
if (!empty($validated['uncertainty_history'])) {
    foreach ($validated['uncertainty_history'] as $item) {
        $equipment->equipmentUncertainties()->create([
             'uncertainty'   => $item['value'],
             'date'          => $item['date_recorded'],
        ]);
    }
}


    return response()->json([
        'id' => $equipment->id,
        'equipment' => $equipment->load('equipmentUncertainties'),
        'message' => 'Equipment created successfully',
    ]);
}


    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    $equipment = Equipment::with('equipmentUncertainties')->findOrFail($id);
    return response()->json($equipment);
}



public function addUncertainty(Request $request, $id)
{
    $equipment = Equipment::findOrFail($id);

    $validated = $request->validate([
        'uncertainty' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    $record = $equipment->equipmentUncertainties()->create([
        'uncertainty' => $validated['uncertainty'],
        'date' => $validated['date'],
    ]);

    return response()->json($record);
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
    $equipment = Equipment::findOrFail($id);

    $validated = $request->validate([
        'alternative_id'   => 'nullable|string|max:255',
        'legacy_id'        => 'nullable|string|max:255',
        'description'      => 'required|string|max:255',
        'serial_number'    => 'nullable|string|max:255',
        'asset_tag'        => 'nullable|string|max:255',
     ]);

    $equipment->update($request->only([
        'alternative_id', 'legacy_id', 'description', 'serial_number', 'asset_tag',
        'size', 'type', 'make', 'model', 'tolerance_basis', 'tolerance',
        'range_capacity', 'range_unit', 'resolution', 'resolution_unit',
        'traceability', 'display_type', 'manufacturer', 'department',
        'custodian', 'location', 'uncertainty', 'uncertainty_unit', 'io',
        'master_equipment', 'equipment_status', 'project_id'
    ]));

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث الجهاز بنجاح!',
        'equipment' => $equipment->load('equipmentUncertainties')
    ]);
}




    /**
     * Remove the specified resource from storage.
     */
    // ================== حذف جهاز واحد ==================
public function destroy(Equipment $equipment)
{
    $equipment->delete();
    return response()->json(['message' => 'تم الحذف']);
}

// ================== حذف أجهزة متعددة ==================
public function deleteMultiple(Request $request)
{
    $ids = $request->ids;
    if ($ids && is_array($ids)) {
        Equipment::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'تم حذف الأجهزة بنجاح']);
    }
    return response()->json(['message' => 'لم يتم تحديد أي جهاز'], 400);
}

}
