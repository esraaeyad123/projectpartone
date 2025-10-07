<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipments';


    protected $fillable = [
        'alternative_id',
        'legacy_id',
        'equipment_reference',
        'description',
        'serial_number',
        'asset_tag',
        'size',
        'type',
        'make',
        'model',
        'tolerance_basis',
        'tolerance',
        'range_capacity',
        'range_unit',
        'resolution',
        'resolution_unit',
        'traceability',
        'display_type',
        'manufacturer',
        'department',
        'custodian',
        'location',
        'uncertainty',
        'uncertainty_unit',
        'io',
        'master_equipment',
        'equipment_status',
        'project_id',
    ];



    public function project()
    {
        return $this->belongsTo(Project::class);
    }

  public function equipmentUncertainties()
    {
        return $this->hasMany(UncertaintyRecord::class, 'equipment_id');
    }
 protected static function boot()
{
    parent::boot();

    static::creating(function ($equipment) {
        // توليد Equipment Reference
        $lastId = Equipment::max('id') + 1;
        $equipment->equipment_reference = 'EQP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        // توليد تاريخ التسجيل (اختياري)
        $equipment->created_at = now();
    });
}


// app/Models/Equipment.php
public function files()
{
    return $this->hasMany(EquipmentFile::class);
}

 public function calibration()
    {
        return $this->hasOne(Calibration::class);
    }

    // Maintenance

 // Equipment.php

// الصيانة لكل جهاز
public function maintenance()
{
    return $this->hasOne(Maintenance::class, 'equipment_id');
}


}
