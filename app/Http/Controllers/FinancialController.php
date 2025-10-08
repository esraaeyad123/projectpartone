<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * عرض الصفحة المالية.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        // 💡 توجيه الكونترولر لعرض ملف financial.blade.php
        return view('financial.index'); 
    }
}