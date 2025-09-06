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
        'owner',
        'consultant',
        'contractor',
    ];

    // علاقة مع العميل
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
            $project->reference = 'AAMC-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // توليد تاريخ التسجيل
            $project->registration_date = now()->toDateString();
        });
    }
}
