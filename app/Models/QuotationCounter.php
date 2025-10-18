<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationCounter extends Model
{
    use HasFactory;


    protected $fillable = ['category', 'last_number'];

}
