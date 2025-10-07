<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Equipment;

class DashboardController extends Controller
{
    public function index()
    {

        $today = Carbon::today();
    $nextWeek = $today->copy()->addDays(7);
    $urgentThreshold = $today->copy()->addDays(3); // تنبيه عاجل إذا قريب جداً

    // Calibration reminders
    $calibrationReminders = Equipment::with('calibration')
        ->whereHas('calibration', function ($q) use ($today, $nextWeek) {
            $q->whereBetween('next_calib_date', [$today, $nextWeek]);
        })
        ->get()
        ->map(function($eq) use ($urgentThreshold) {
            $eq->calib_urgent = $eq->calibration && $eq->calibration->next_calib_date <= $urgentThreshold;
            return $eq;
        });

    // Maintenance reminders
    $maintenanceReminders = Equipment::with('maintenance')
        ->whereHas('maintenance', function ($q) use ($today, $nextWeek) {
            $q->whereBetween('next_maint_date', [$today, $nextWeek]);
        })
        ->get()
        ->map(function($eq) use ($urgentThreshold) {
            $eq->maint_urgent = $eq->maintenance && $eq->maintenance->next_maint_date <= $urgentThreshold;
            return $eq;
        });


        return view('dashboard.index', compact('calibrationReminders', 'maintenanceReminders'));
    }

}
