<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerFileController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Quotation\QuotationLineController;
use App\Http\Controllers\Project\ProjectContactController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\Quotation\QuotationHeaderController;
use App\Http\Controllers\Quotation\PriceListController;
use App\Http\Controllers\Employees\EmployeesContactController;
use App\Http\Controllers\Employees\EmployeesController;
use App\Http\Controllers\Employees\EmployeeFileController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Test\TestFileController;
use App\Http\Controllers\Test\UncertaintyController;
use App\Http\Controllers\Equipment\EquipmentController;







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
//Route::post('/project-contacts', [ProjectContactController::class, 'store']);
//Route::put('/project-contacts/{id}', [ProjectContactController::class, 'update']);

// استرجاع جهة اتصال واحدة
//Route::get('/project-contacts/{contact}', [ProjectContactController::class, 'show']);
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

//Route::delete('/contacts/delete-multiple', [ProjectContactController::class, 'deleteMultiple']);


//Route::post('/project-contacts', [ProjectContactController::class, 'store']);
//Route::put('/project-contacts/{id}', [ProjectContactController::class, 'update']);
//Route::get('/project-contacts/{contact}', [ProjectContactController::class, 'show']);
//Route::delete('/contacts/delete-multiple', [ProjectContactController::class, 'deleteMultiple']);
Route::get('/quotation', [QuotationHeaderController::class, 'index'])->name('quotation.index');
Route::get('/quotation/projects', [QuotationHeaderController::class, 'getProjects']);
Route::get('/quotation/contacts', [QuotationHeaderController::class, 'getContacts']);
Route::post('/quotation/save-header', [QuotationHeaderController::class, 'saveHeader']);
Route::post('/quotations/delete', [QuotationHeaderController::class, 'deleteSelected'])->name('quotations.delete');
Route::get('/quotations/list',    [QuotationHeaderController::class, 'list']);
Route::get('/quotations/{id}',    [QuotationHeaderController::class, 'show']);
Route::put('/quotations/{id}',    [QuotationHeaderController::class, 'update']);
Route::post('/quotations/{id}/generate-pdf', [QuotationHeaderController::class, 'generatePdf'])->name('quotations.generatePdf');
Route::get('/quotations/lines/data',   [QuotationLineController::class, 'getLines']);
Route::post('/quotations/lines/store', [QuotationLineController::class, 'storeLine'])->name('quotations.lines.store');
Route::get('/price-lists', [PriceListController::class, 'index']); // لجلب البيانات في DataTable
Route::post('/price-lists', [PriceListController::class, 'store']); // لحفظ السعر الجديد
Route::post('/price-lists/selected', [PriceListController::class, 'getSelected']);
Route::post('/quotation-lines/bulk-add', [QuotationLineController::class, 'bulkAdd']);
Route::get('/quotation-lines/{quotationId}', [QuotationLineController::class, 'getByQuotation']);
// routes/web.php أو routes/api.php
Route::post('/quotations/{quotation}/lines/store', [QuotationLineController::class, 'storeLine']);




// ======================= Employee Routes (التصحيح النهائي) =======================

// 🌟🌟 (1) يجب أن يكون DELETE ليتطابق مع _method: 'DELETE' في AJAX
Route::delete('/employees/delete-multiple', [EmployeesController::class, 'deleteMultiple'])->name('employees.deleteMultiple');

// (2) المسار الخاص بجلب بيانات الجدول
Route::get('/employees/data', [EmployeesController::class, 'getEmployeesData'])->name('employees.data');

// (3) المسار الرئيسي (index)
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');

// (4) إنشاء موظف جديد
Route::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');

// (5) المسارات التي تستخدم متغير {employee} يجب أن تكون في النهاية وذات قيد رقمي
Route::get('/employees/{employee}', [EmployeesController::class, 'show'])->name('employees.show')->where('employee', '[0-9]+');
Route::put('/employees/{employee}', [EmployeesController::class, 'update'])->name('employees.update')->where('employee', '[0-9]+');
Route::delete('/employees/{employee}', [EmployeesController::class, 'destroy'])->name('employees.destroy')->where('employee', '[0-9]+');



// ======================= Employee Routes =======================

// جلب كل الموظفين

// حذف عدة موظفين دفعة واحدة

// جلب وحفظ وتعديل وحذف جهات الاتصال الخاصة بالموظفين
Route::prefix('employees/{employee}')->group(function () {
    // جلب كل جهات الاتصال لموظف محدد
    Route::get('contacts', [EmployeesContactController::class, 'index']);

    // إنشاء جهة اتصال جديدة لموظف
    Route::post('contacts', [EmployeesContactController::class, 'store']);

    // تعديل جهة اتصال موجودة لموظف
    Route::put('contacts/{contact}', [EmployeesContactController::class, 'update']);
});


// حذف عدة جهات اتصال دفعة واحدة
// هذا لازم يكون قبل أي Route فيه {employee_contact}
// حذف متعدد لازم ييجي أول
Route::delete('employee-contacts/delete-multiple', [EmployeesContactController::class, 'deleteMultiple'])
    ->name('employee-contacts.delete-multiple');

// حذف جهة اتصال واحدة بالـ ID
Route::delete('employee-contacts/{employee_contact}', [EmployeesContactController::class, 'destroy'])
    ->name('employee-contacts.destroy');



Route::get('employees/{employee}/files', [EmployeeFileController::class, 'index']);
Route::post('employees/{employee}/files', [EmployeeFileController::class, 'store']);
Route::get('employees/files/{employeeFile}/download', [EmployeeFileController::class, 'download']);
Route::post('employees/files/download-multiple', [EmployeeFileController::class, 'downloadMultipleFiles']);
Route::delete('employees/files/{employeeFile}', [EmployeeFileController::class, 'destroy']);
Route::post('employees/files/delete-multiple', [EmployeeFileController::class, 'destroyMultiple']);
Route::get('employees/{employee}/files-json', [EmployeeFileController::class, 'filesJson']);
Route::get('/employees/files/{file}/view', [EmployeeFileController::class, 'viewEmployeeFile'])->name('employees.files.view');


// إنشاء كل روابط CRUD للـ Test
Route::resource('tests', TestController::class);
Route::get('/tests/{testId}/files', [TestFileController::class, 'files'])
    ->name('tests.files');
Route::post('/tests/delete-multiple', [TestController::class, 'deleteMultiple']);
Route::post('/uncertainty-history', [UncertaintyController::class, 'store'])->name('uncertainty-history.store');
Route::delete('/uncertainty-history/{id}', [UncertaintyController::class, 'destroy'])->name('uncertainty-history.destroy');
Route::get('/uncertainty-history/{testId}', [UncertaintyController::class, 'index'])->name('uncertainty-history.index');

// رفع ملف واحد للاختبار
Route::post('/tests/{test}/files', [TestFileController::class, 'store'])->name('tests.files.store');

Route::post('/tests/{test}/files', [TestFileController::class, 'store'])
    ->name('tests.files.store');

Route::get('/tests/{test}/files-json', [TestFileController::class, 'filesJson'])
    ->name('tests.files.json');

// عرض ملف
Route::get('/tests/files/{file}/view', [TestFileController::class, 'view'])
    ->name('tests.files.view');


// تحميل ملف
Route::get('/tests/files/{file}/download', [TestFileController::class, 'download'])
    ->name('tests.files.download');

// حذف ملف
Route::delete('/tests/files/{file}', [TestFileController::class, 'destroy'])
    ->name('tests.files.destroy');


Route::resource('equipment', EquipmentController::class);
