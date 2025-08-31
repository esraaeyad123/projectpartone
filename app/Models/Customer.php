<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

   protected $fillable = [
        'customer_id',
        'customer_name',
        'arabic_name',
        'customer_legal_name',
        'customer_type',
        'potential',
        'legacy_acc_no',
        'date_registered',
        'phone',
        'country',
        'arabic_location',
        'city',
        'district',
        'street',
        'post_code',
        'address_block',
        'po_box',
        'building_no',
        'payment_terms',
        'discount',
        'cash',
        'credit_limit',
        'vat_profile',
        'trn_tin',
        'registration_no',
        'restrict_deliveries',
        'restrict_orders',
        'restrict_quotations',
    ];
public function contacts() {
    return $this->hasMany(Contact::class);
}

  protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_id)) {
                $lastCustomer = Customer::orderBy('id', 'desc')->first();

                if ($lastCustomer && preg_match('/AAMC-(\d+)/', $lastCustomer->customer_id, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                } else {
                    $nextNumber = 1001; // البداية
                }

                // لو موجود بالفعل زود الرقم
                do {
                    $newId = 'AAMC-' . $nextNumber;
                    $exists = Customer::where('customer_id', $newId)->exists();
                    $nextNumber++;
                } while ($exists);

                $customer->customer_id = $newId;
            }
        });
    }
}
