<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'confirmation_id',
        'service_id',
        'service_name',
        'method',
        'unit',
        'price',
        'price_only',
        'quantity',
        'total',
    ];

    public function confirmation()
    {
        return $this->belongsTo(Confirmation::class);
    }

    public function service()
    {
        return $this->belongsTo(TestAndService::class, 'service_id');
    }
}
