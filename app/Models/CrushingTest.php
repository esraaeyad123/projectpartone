<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrushingTest extends Model
{
    use HasFactory;
      protected $fillable = [
        'sample_id','test_method','r_age','a_age','price','qty','scheduled_at',
        'rem','test_location','report_no','rev','items'
    ];

    public function sample() {
        return $this->belongsTo(Sample::class);
    }
}
