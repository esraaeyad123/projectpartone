<?php

namespace App\Http\Controllers\ReportApproval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;


class ReportApprovalController extends Controller
{
   public function index()
    {
         $cc = Customer::all();
        // تقدر تمرر بيانات إلى الصفحة هنا لاحقًا
        return view('report_approval.index', compact('cc'));
    }

    public function show($id)
{
    // مستقبلاً تجيب التقرير من قاعدة البيانات
    return view('report_approval.details', compact('id'));
}
}
