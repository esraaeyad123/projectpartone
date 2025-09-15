<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id', 'service_test_id', 'description', 'accounted',
        'category', 'type', 'method', 'price'
    ];

    public function quotation() {
        return $this->belongsTo(QuotationHeader::class, 'quotation_id');
    }
}
