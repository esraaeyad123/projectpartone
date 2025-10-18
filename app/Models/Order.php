<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_no','project_id','lab','order_date','s_contractor','payment_terms','pouring_ticket'];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function samples() {
        return $this->hasMany(Sample::class);
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        $lastId = Order::max('id') + 1;
        $order->order_no = 'ORD-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $order->order_date = now();
    });
}

}
