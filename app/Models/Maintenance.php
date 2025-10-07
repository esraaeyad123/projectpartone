<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'equipment_id',
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
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
