<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
    'reference',
    'name',
    'arabic_name',
    'registration_date',
    'region',
    'customer_id',
    'owner_id',
    'consultant_id',
    'contractor_id',
    'project_details'
    ];

  public function customer() {
    return $this->belongsTo(Customer::class, 'customer_id');
}

   public function confirmations()
    {
        return $this->hasMany(Confirmation::class);
    }


public function owner() {
    return $this->belongsTo(Customer::class, 'owner_id');
}

public function consultant() {
    return $this->belongsTo(Customer::class, 'consultant_id');
}

public function contractor() {
    return $this->belongsTo(Customer::class, 'contractor_id');
}


    public function contacts()
{
    return $this->hasMany(ProjectContact::class);
}


public function files()
{
    return $this->hasMany(ProjectFile::class);
}

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // توليد Reference
            $lastId = Project::max('id') + 1;
            $project->reference = 'AAMP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // توليد تاريخ التسجيل
            $project->registration_date = now()->toDateString();
        });
    }

    public function quotations()
{
    return $this->hasMany(QuotationHeader::class, 'project_id', 'id');
}


public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }


     public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
