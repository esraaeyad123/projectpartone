<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationFile extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = [
        'confirmation_id',
        'name',
        'path',
        'type',
        'size',
    ];

    public function confirmation()
    {
        return $this->belongsTo(Confirmation::class, 'confirmation_id');
    }
}
