<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

     protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'mobile',
        'position',
        'is_primary',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
