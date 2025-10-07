<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// تأكدي من استيراد النموذج (Model) الخاص بالتعميدات
use App\Models\Confirmation; // <== يجب أن يتطابق هذا المسار مع مكان إنشاء النموذج

class ConfirmationController extends Controller
{
    public function index(Request $request)
    {
        // 1. جلب جميع سجلات التعميدات
        $confirmations = Confirmation::all();

        // 2. عرض صفحة confirmation.index وتمرير البيانات إليها
        return view('confirmation.index', compact('confirmations')); 
    }
}