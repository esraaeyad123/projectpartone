<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestFile extends Model
{
    use HasFactory;
      protected $fillable = ['test_id', 'file_name', 'file_path', 'file_type', 'uploaded_by', 'description', 'is_active'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
