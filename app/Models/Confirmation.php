<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
 use HasFactory;

    protected $fillable = [
        'category',
        'confirm_id',
        'confirm_date',
        'project_id',
        'customer_id',
        'subject',
        'conf_source',
        'contract_no',
        'contact_person',
        'conf_to',
        'currency',
        'discount',
        'tax',
        'validity',
        'payment_terms',
    ];

    /**
     * علاقات الموديل
     */

    // علاقة مع المشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // علاقة مع العميل
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function lines()
{
    return $this->hasMany(ConfirmationLine::class);
}
    // علاقة مع خطوط التعميد


    /**
     * Boot method لتوليد confirm_id تلقائيًا عند الإنشاء
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($confirmation) {
            // توليد Confirm ID
            $lastId = static::max('id') + 1;
            $confirmation->confirm_id = 'CONF-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // توليد التاريخ الحالي إذا لم يكن محدد
            if (!$confirmation->confirm_date) {
                $confirmation->confirm_date = now()->format('Y-m-d');
            }
        });
    }}
