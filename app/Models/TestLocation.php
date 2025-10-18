<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestLocation extends Model
{
    use HasFactory;
     use HasFactory;
    protected $fillable = ['name'];

    public function crushingTests() {
        return $this->hasMany(CrushingTest::class, 'test_location_id');
    }
}
