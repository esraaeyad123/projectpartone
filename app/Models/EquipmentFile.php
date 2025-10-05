<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'name',
        'path',
        'type',
        'size',
    ];

    /**
     * العلاقة مع المعدات
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
