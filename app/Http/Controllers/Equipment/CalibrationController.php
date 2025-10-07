<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Models\Calibration;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    public function index($equipmentId)
    {
        $calibrations = Calibration::where('equipment_id', $equipmentId)->get();
        return response()->json($calibrations);
    }






 public function showByEquipment($equipmentId)
{
    $calibration = Calibration::where('equipment_id', $equipmentId)->first();
    if (!$calibration) {
        return response()->json(['message' => 'No calibration found for this equipment'], 404);
    }
    return response()->json($calibration);
}


public function store(Request $request, $equipmentId)
    {
        $data = $request->validate([
            'calib_method' => 'nullable|string',
            'calib_procedure_no' => 'nullable|string',
            'last_calib_date' => 'nullable|date',
            'next_calib_date' => 'nullable|date',
            'reminder' => 'boolean',
            'has_next_calibration' => 'boolean',
            'calibrated_externally' => 'boolean',
            'provider' => 'nullable|string',
            'internally_by' => 'nullable|string',
            'last_certificate' => 'nullable|string',
            'calibration_status' => 'in:completed,scheduled,overdue',
        ]);

        $data['equipment_id'] = $equipmentId;

        $calibration = Calibration::create($data);

        return response()->json([
            'success' => true,
            'calibration' => $calibration
        ]);
    }

    // تعديل معايرة موجودة
    public function update(Request $request, $id)
    {
        $calibration = Calibration::findOrFail($id);

        $calibration->calib_method          = $request->calib_method;
        $calibration->calib_procedure_no    = $request->calib_procedure_no;
        $calibration->last_calib_date       = $request->last_calib_date;
        $calibration->scheduled             = $request->scheduled;
        $calibration->next_calib_date       = $request->next_calib_date;
        $calibration->reminder              = $request->reminder ? 1 : 0;
        $calibration->has_next_calibration  = $request->has_next_calibration ? 1 : 0;
        $calibration->only_one              = $request->only_one ? 1 : 0;
        $calibration->calibrated_externally = $request->calibrated_externally ? 1 : 0;

        $calibration->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Calibration updated successfully',
            'calibration' => $calibration
        ]);
    }


}
