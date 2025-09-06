<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerFileController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectContactController;
use App\Http\Controllers\ProjectFileController;




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


Route::resource('projects', ProjectController::class);

Route::prefix('projects/{project}')->group(function () {
    Route::resource('contacts', ProjectContactController::class);
});

Route::post('/projects/delete-multiple', [ProjectController::class, 'deleteMultiple']);


// routes/web.php
// جلب جهات الاتصال الخاصة بمشروع معين
Route::get('/projects/{project}/contacts', [ProjectContactController::class, 'index']);

// إضافة جهة اتصال جديدة
Route::post('/project-contacts', [ProjectContactController::class, 'store']);
Route::put('/project-contacts/{id}', [ProjectContactController::class, 'update']);

// استرجاع جهة اتصال واحدة
Route::get('/project-contacts/{contact}', [ProjectContactController::class, 'show']);
Route::post('/project-contacts/delete-multiple', [ProjectContactController::class, 'deleteMultiple']);

Route::prefix('projects/{project}')->group(function () {
    Route::get('files', [ProjectFileController::class, 'index'])->name('projects.files.index');
    Route::post('files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::delete('files/{file}', [ProjectFileController::class, 'destroy'])->name('projects.files.destroy');
    Route::get('files/{file}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
});


Route::get('/projects/{project}/files-json', function($projectId) {
    return \App\Models\ProjectFile::where('project_id', $projectId)->get();
});
 Route::post('{projectId}/files', [ProjectFileController::class, 'upload']);
Route::get('{projectId}/files-json', [ProjectFileController::class, 'filesJson']);
// routes/web.php
Route::post('/projects/files/download-multiple', [ProjectFileController::class, 'downloadMultipleFiles'])->name('files.downloadMultiple');

// routes/web.php
Route::delete('/projects/files/{file}', [ProjectFileController::class, 'destroy'])->name('files.destroy');
Route::post('/projects/files/delete-multiple', [ProjectFileController::class, 'destroyMultiple'])->name('files.destroyMultiple');
// routes/web.php
Route::post('/projects/files/download-multiple', [ProjectFileController::class, 'downloadMultipleFiles'])->name('files.downloadMultiple');
Route::get('/projects/files/{id}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
