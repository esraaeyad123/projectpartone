<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerFileController;



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

// Route
Route::get('/contacts/export', [ContactController::class, 'exportAll'])->name('contacts.export');
// routes/web.php

Route::post('/customers/export-selected', [CustomerController::class, 'exportSelected'])
     ->name('customers.export.selected');





// Route مخصص للصفحة
Route::get('/customer-files/{customerId}', [CustomerFileController::class, 'index'])
    ->name('customer-files.index');
// بعده فقط تضيف resource
Route::resource('customer-files', CustomerFileController::class)
    ->except(['index']); // استثناء index لأنه لدينا route مخصص

Route::get('customer-files/{customer}/files-json', [CustomerFileController::class, 'filesJson']);
// عرض ملف
Route::get('/customer-files/{id}/view', [CustomerFileController::class, 'view'])->name('customer-files.view');

Route::get('customer-files/{id}/download', [CustomerFileController::class, 'download'])->name('customer-files.download');


