<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        // Invoice Information
        'invoice_no',
        'invoice_date',
        'department',
        'prof_date',
        'account_date',
        'due_date',
        'project_code',
        'project_name',
        'contract_no',
        'project',

        // Foreign Keys
        'project_id',
        'customer_id',

        // Customer Information
        'customer_id_ref',
        'account_no',
        'trn_no',
        'location',

        // Contact Information
        'account_manager',
        'contact_person',
        'attn_to',
        'attn_pos',
        'address_email',

        // Terms & Controls
        'payment_terms',
        'payment_method',
        'vat_profile',
        'discount_pct',
        'sales_tax_pct',
        'retention_pct',
        'currency',

        // الحسابات
        'net_amount',
        'vat_amount',
        'total_due',
        'status',
        'items_count',
    ];

    /**
     * ✅ العلاقات
     */

    // فاتورة تخص مشروع واحد
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // فاتورة تخص عميل واحد
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ✅ Scope للبحث حسب رقم الفاتورة أو العميل
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $term = "%$term%";
            $query->where('invoice_no', 'like', $term)
                  ->orWhere('project_name', 'like', $term)
                  ->orWhere('department', 'like', $term);
        }
    }

       protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // توليد رقم الفاتورة التلقائي
            $lastId = Invoice::max('id') + 1;
            $invoice->invoice_no = 'INV-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

            // ضبط تاريخ الفاتورة الافتراضي
            if (empty($invoice->invoice_date)) {
                $invoice->invoice_date = now()->toDateString();
            }

            // الحالة الافتراضية
            if (empty($invoice->status)) {
                $invoice->status = 'Draft';
            }
        });
    }

}

