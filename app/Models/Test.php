<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;


    // الحقول التي يمكن ملؤها بشكل جماعي (Mass Assignment)
    protected $fillable = [
       'test_code',
        'short_name',
        'service_group',
        'department',
        'generate_report',
        'description',
        'type',
        'activity_type',
        'date_added',
        'location',
        'test_method',
        'template_name',
        'template_type',
        'file_template',
        'report_designation',
        'report_title',
        'built_in_template',
        'element',
        'uncertainty',
        'use_uncertainty',
        'unit_price',
    ];


   protected static function boot()
    {
        parent::boot();

        static::creating(function ($test) {
            // توليد Test Code تلقائي
            $lastId = Test::max('id') + 1;
            $test->test_code = 'TEST-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // توليد تاريخ الإضافة تلقائي إذا لم يكن موجود
            if (empty($test->date_added)) {
                $test->date_added = now()->toDateString();
            }
        });
    }
  public function uncertainties() {
    return $this->hasMany(Uncertainty::class, 'test_id');
}

    public function files()
    {
        return $this->hasMany(TestFile::class);
    }

}
