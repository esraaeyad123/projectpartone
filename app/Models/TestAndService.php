<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAndService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'method', 'unit', 'default_price', 'category', 'active',
    ];

    public function lines()
    {
        return $this->hasMany(ConfirmationLine::class, 'service_id');
    }
}
