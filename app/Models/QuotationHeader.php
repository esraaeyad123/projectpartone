<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'project_id', 'contact_id',
        'quote_category', 'quote_no', 'rev', 'quote_date', 'legacy_no', 'legacy_date',
        'subject', 'currency', 'discount', 'vat', 'validity_days',
        'payment_terms', 'method', 'remarks', 'quote_file',
        'file_status', 'declined', 'declined_message',
        'total_lines', 'discount_amount', 'tax_amount', 'grand_total',
               'inquiry',
        'contact_from',
        'attn_to',
        'attn_pos',
        'contact_email',
        'contact_mobile',
        'use_alt_form',
        'overall_status',
        'last_confirmation',
        'last_confirmed',
       'project_details'

    ];

    // العلاقات
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
    return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

}
