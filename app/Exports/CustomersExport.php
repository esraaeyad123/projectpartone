<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $ids;
    protected $all;

    public function __construct($all = false, $ids = [])
    {
        $this->all = $all;
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = Customer::query()->select(
            'id',
            'customer_name',
            'arabic_name',
            'customer_legal_name',
            'customer_type',
            'phone',
            'country',
            'city',
            'district',
            'street',
            'post_code',
            'payment_terms',
            'discount',
            'vat_profile',
            'trn_tin',
            'registration_no',
            'credit_limit',
            'cash',
            'potential'
        );

        if (!$this->all && !empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'رقم العميل',
            'اسم العميل',
            'الاسم بالعربية',
            'الاسم القانوني',
            'نوع العميل',
            'الهاتف',
            'الدولة',
            'المدينة',
            'الحي',
            'الشارع',
            'الرمز البريدي',
            'شروط الدفع',
            'الخصم',
            'ملف الضريبة VAT',
            'الرقم الضريبي',
            'رقم التسجيل',
            'حد الائتمان',
            'نقدي',
            'عميل محتمل',
        ];
    }
}
