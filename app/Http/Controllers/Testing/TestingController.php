<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;

class TestingController extends Controller
{
    public function index()
    {
          $customers = Customer::with('contacts')->get();
    $projects = Project::all();
    return view('testing.index', compact('customers', 'projects'));
    }
}
