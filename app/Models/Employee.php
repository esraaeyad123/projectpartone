<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_reference',
        'initials',
        'first_name',
        'mid_name',
        'last_name',
        'full_name',
        'email',
        'supervisor_id',
        'ctta',
        'business_unit',
        'department',
        'title',
        'job_rules'
    ];

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function contacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            // توليد Employee Reference
            $lastId = Employee::max('id') + 1;
            $employee->employee_reference  = 'EMP-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // توليد تاريخ التسجيل (اختياري)
            $employee->created_at = now(); // أو $employee->registration_date إذا لديك حقل
        });
    }

    
    public function files()
    {
        return $this->hasMany(EmployeeFile::class);
    }
}
