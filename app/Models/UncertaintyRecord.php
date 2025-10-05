<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UncertaintyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'uncertainty',
        'date',
    ];

   public function equipment()
{
    return $this->belongsTo(Equipment::class);
}

}
