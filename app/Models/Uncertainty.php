<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uncertainty extends Model
{
    use HasFactory;

protected $fillable = ['test_id', 'value', 'date_recorded'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
