<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
    use HasFactory;
        protected $table = 'project_contacts';


     protected $fillable = [
        'project_id','name','email','phone','mobile','position','is_primary'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
