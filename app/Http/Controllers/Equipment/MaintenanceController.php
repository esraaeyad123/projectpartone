<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function store(Request $request, $equipmentId)
    {
        $data = $request->validate([
            'last_maint_date' => 'nullable|date',
            'scheduled_date' => 'nullable|date',
            'next_maint_date' => 'nullable|date',
            'scheduled_maint' => 'boolean',
            'has_next_maint' => 'boolean',
            'reminder_maint' => 'boolean',
            'maint_externally' => 'boolean',
            'only_one' => 'boolean',
            'occurrence' => 'nullable|string',
            'maint_provider' => 'nullable|string',
            'maint_internally_by' => 'nullable|string',
            'maint_status' => 'in:completed,scheduled,overdue,pending',
        ]);

        $data['equipment_id'] = $equipmentId;

        $maintenance = Maintenance::create($data);

        return response()->json([
            'success' => true,
            'message' => '✅ Maintenance record added successfully',
            'maintenance' => $maintenance
        ]);
    }

    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update($request->only([
            'last_maint_date',
            'scheduled_date',
            'next_maint_date',
            'scheduled_maint',
            'has_next_maint',
            'reminder_maint',
            'maint_externally',
            'only_one',
            'occurrence',
            'maint_provider',
            'maint_internally_by',
            'maint_status',
        ]));

        return response()->json([
            'success' => true,
            'message' => '✅ Maintenance record updated successfully',
            'maintenance' => $maintenance
        ]);
    }
}
