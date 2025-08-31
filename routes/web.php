<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/customers/data', [CustomerController::class, 'getCustomersData'])->name('customers.data');
Route::post('/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');

Route::resource('customers', CustomerController::class);

// contacts routes inside customers
Route::resource('customers.contacts', ContactController::class);


Route::delete('/contacts/delete-multiple', [ContactController::class, 'deleteMultiple']);

