<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id','sample_no','sample_type','sample_source','sample_description',
        'rfi_wir','structure_ref','sampling_method','received_at','total_received',
        'spec_prop_by','sampled_at','received_by','sampled_by','casted_at','samp_brought_by',
        'comp_equipment','witness','mtd_of_comp','cement_content','notes','cement_type',
        'nominal_size','class'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function crushingTests() {
        return $this->hasMany(CrushingTest::class);
    }



    protected static function boot()
{
    parent::boot();

    static::creating(function ($sample) {
        // توليد رقم العينة
        $lastId = Sample::max('id') + 1;
        $sample->sample_no = 'SMP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        // حفظ وقت الاستلام (اختياري)
        if (!$sample->received_at) {
            $sample->received_at = now();
        }
    });
}


public function project() {
    return $this->belongsTo(Project::class);
}


}
