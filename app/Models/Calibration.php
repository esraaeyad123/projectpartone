<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calibration extends Model
{
    use HasFactory;
    protected $fillable = [
        'equipment_id',
        'calib_method',
        'calib_procedure_no',
        'last_calib_date',
        'next_calib_date',
        'reminder',
        'has_next_calibration',
        'calibrated_externally',
        'provider',
        'internally_by',
        'last_certificate',
        'calibration_status',
        'only_one',
        'scheduled'

    ];

    // علاقة مع الأجهزة
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
