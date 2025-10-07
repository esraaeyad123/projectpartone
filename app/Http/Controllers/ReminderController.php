<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Equipment;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $calibrationReminders = Equipment::with('calibration')
            ->whereHas('calibration', function($q) use ($today) {
                $q->where('reminder', 1)
                  ->where('next_calib_date', '<=', $today->addDays(7));
            })->get();

        $maintenanceReminders = Equipment::with('maintenance')
            ->whereHas('maintenance', function($q) use ($today) {
                $q->where('reminder_maint', 1)
                  ->where('next_maint_date', '<=', $today->addDays(7));
            })->get();

        return view('dashboard.index', compact('calibrationReminders', 'maintenanceReminders'));
    }
}
