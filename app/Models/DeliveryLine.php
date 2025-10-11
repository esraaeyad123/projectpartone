<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLine extends Model
{
    use HasFactory;
     protected $fillable = [
        'delivery_id', 'name', 'method', 'unit', 'price', 'price_only', 'quantity'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
