<?php

namespace App\Http\Controllers\Confirmation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Confirmation;
use App\Models\Project ;
use App\Models\ConfirmationLine;
use Illuminate\Support\Facades\DB;



class ConfirmationController extends Controller
{
    public function index(Request $request)
{
    if ($request->expectsJson()) {
        // جلب كل التعميدات مع العلاقات المرتبطة
        return Confirmation::with(['customer', 'project'])->get();
    }

    // للعرض في صفحة Blade
    $confirmations = Confirmation::with(['customer', 'project'])->get();
    $customers = \App\Models\Customer::all();
    $projects = Project::with('contacts')->get();


    return view('confirmation.index', compact('confirmations', 'customers', 'projects'));
}





}
