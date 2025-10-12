<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Equipment;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


public function boot()
{
    // البيانات التي تريد أن تظهر في كل الصفحات (الهيدر)
    View::composer('*', function ($view) {

        $today = Carbon::today();
        $nextWeek = $today->copy()->addDays(7);
        $urgentThreshold = $today->copy()->addDays(3);

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

        $view->with(compact('calibrationReminders', 'maintenanceReminders'));
    });
}

}
