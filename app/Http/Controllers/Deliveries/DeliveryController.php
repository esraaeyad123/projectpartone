<?php

namespace App\Http\Controllers\Deliveries;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
{
    // افترض أن ملف الواجهة اسمه 'deliveries/index.blade.php'
    return view('deliveries.index');
}
}