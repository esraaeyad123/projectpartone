<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
     protected $fillable = [
        'delivery_no', 'delivery_date', 'department',
        'project_id', 'project_code', 'project_no', 'project_name', 'project_details',
        'customer_id', 'customer_id_ref', 'account_no', 'location',
        'contact_person', 'attn_to', 'attn_pos', 'address_email',
        'prepared_by', 'delivered_by', 'received_by', 'date_received','status'
    ];

    // علاقة مع العميل
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // علاقة مع التفاصيل
    public function lines()
    {
        return $this->hasMany(DeliveryLine::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($delivery) {
        // توليد رقم السنة الحالية (آخر رقمين فقط)
        $year = now()->format('y'); // مثال: 24

        // الحصول على آخر رقم تسليم (حسب السنة)
        $lastDelivery = Delivery::whereYear('created_at', now()->year)->latest('id')->first();

        if ($lastDelivery && preg_match('/AAM-DN-' . $year . '-(\d+)/', $lastDelivery->delivery_no, $matches)) {
            $lastNumber = intval($matches[1]);
        } else {
            $lastNumber = 0;
        }

        // زيادة الرقم واحد
        $nextNumber = $lastNumber + 1;

        // تنسيق الرقم مع أصفار أمامية (6 خانات)
        $formattedNumber = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // توليد رقم التسليم الكامل
        $delivery->delivery_no = "AAM-DN-{$year}-{$formattedNumber}";
    });
}

}
