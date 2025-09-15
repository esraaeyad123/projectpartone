<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'method',
        'unit',
        'price',
        'price_only',
        'active',
    ];

    // علاقة مع quotation_lines
    public function quotationLines()
    {
        return $this->hasMany(QuotationLine::class, 'price_list_id');
    }
}
