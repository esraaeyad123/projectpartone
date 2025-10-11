<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerFileController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectContactController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\Quotation\QuotationHeaderController;
use App\Http\Controllers\Quotation\QuotationLineController;
use App\Http\Controllers\Quotation\PriceListController;
use App\Http\Controllers\Employees\EmployeesController;
use App\Http\Controllers\Employees\EmployeesContactController;
use App\Http\Controllers\Employees\EmployeeFileController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Test\TestFileController;
use App\Http\Controllers\Test\UncertaintyController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Equipment\EquipmentFileController;
use App\Http\Controllers\Equipment\CalibrationController;
use App\Http\Controllers\Equipment\MaintenanceController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportApproval\ReportApprovalController;
use App\Http\Controllers\Confirmation\ConfirmationController;
use App\Http\Controllers\Confirmation\ConfirmationLineController;
use App\Http\Controllers\Confirmation\ConfirmationFileController;
use App\Http\Controllers\Deliveries\DeliveryController;
use App\Http\Controllers\Financial\FinancialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


// ======================= Customers =======================
Route::get('/customers/data', [CustomerController::class, 'getCustomersData'])->name('customers.data');
Route::post('/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');
Route::post('/customers/export-selected', [CustomerController::class, 'exportSelected'])->name('customers.export.selected');
Route::resource('customers', CustomerController::class);

// Contacts inside Customers
Route::resource('customers.contacts', ContactController::class);
Route::delete('/contacts/delete-multiple', [ContactController::class, 'deleteMultiple']);
Route::get('/contacts/export', [ContactController::class, 'exportAll'])->name('contacts.export');

// Customer files
Route::get('/customer-files/{customerId}', [CustomerFileController::class, 'index'])->name('customer-files.index');
Route::resource('customer-files', CustomerFileController::class)->except(['index']);
Route::get('customer-files/{customer}/files-json', [CustomerFileController::class, 'filesJson']);
Route::get('/customer-files/{id}/view', [CustomerFileController::class, 'view'])->name('customer-files.view');
Route::get('customer-files/{id}/download', [CustomerFileController::class, 'download'])->name('customer-files.download');


// ======================= Projects =======================
Route::resource('projects', ProjectController::class);
Route::post('/projects/delete-multiple', [ProjectController::class, 'deleteMultiple']);

// Project Contacts
Route::prefix('projects/{project}')->group(function () {
    Route::resource('contacts', ProjectContactController::class);
    Route::post('contacts/delete-multiple', [ProjectContactController::class, 'deleteMultiple']);
});

// Project Files
Route::prefix('projects/{project}')->group(function () {
    Route::get('files', [ProjectFileController::class, 'index'])->name('projects.files.index');
    Route::post('files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::delete('files/{file}', [ProjectFileController::class, 'destroy'])->name('projects.files.destroy');
    Route::get('files/{file}/download', [ProjectFileController::class, 'download'])->name('projects.files.download');
});
Route::get('/projects/{project}/files-json', [ProjectFileController::class, 'filesJson']);
Route::post('/projects/files/delete-multiple', [ProjectFileController::class, 'destroyMultiple'])->name('files.destroyMultiple');
Route::post('/projects/files/download-multiple', [ProjectFileController::class, 'downloadMultipleFiles'])->name('files.downloadMultiple');


// ======================= Quotations =======================
Route::get('/quotation', [QuotationHeaderController::class, 'index'])->name('quotation.index');
Route::get('/quotation/projects', [QuotationHeaderController::class, 'getProjects']);
Route::get('/quotation/contacts', [QuotationHeaderController::class, 'getContacts']);
Route::post('/quotation/save-header', [QuotationHeaderController::class, 'saveHeader']);
Route::post('/quotations/delete', [QuotationHeaderController::class, 'deleteSelected'])->name('quotations.delete');
Route::get('/quotations/list', [QuotationHeaderController::class, 'list']);
Route::get('/quotations/{id}', [QuotationHeaderController::class, 'show']);
Route::put('/quotations/{id}', [QuotationHeaderController::class, 'update']);
Route::post('/quotations/{id}/generate-pdf', [QuotationHeaderController::class, 'generatePdf'])->name('quotations.generatePdf');

// Quotation Lines
Route::get('/quotations/lines/data', [QuotationLineController::class, 'getLines']);
Route::post('/quotations/lines/store', [QuotationLineController::class, 'storeLine'])->name('quotations.lines.store');
Route::post('/quotation-lines/bulk-add', [QuotationLineController::class, 'bulkAdd']);
Route::get('/quotation-lines/{quotationId}', [QuotationLineController::class, 'getByQuotation']);

Route::get('/price-lists', [PriceListController::class, 'index']);
Route::post('/price-lists', [PriceListController::class, 'store']);
Route::post('/price-lists/selected', [PriceListController::class, 'getSelected']);
Route::post('/quotations/{quotation}/lines/store', [QuotationLineController::class, 'storeLine']);


// ======================= Employees =======================
Route::delete('/employees/delete-multiple', [EmployeesController::class, 'deleteMultiple'])->name('employees.deleteMultiple');
Route::get('/employees/data', [EmployeesController::class, 'getEmployeesData'])->name('employees.data');
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');
Route::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}', [EmployeesController::class, 'show'])->name('employees.show')->where('employee', '[0-9]+');
Route::put('/employees/{employee}', [EmployeesController::class, 'update'])->name('employees.update')->where('employee', '[0-9]+');
Route::delete('/employees/{employee}', [EmployeesController::class, 'destroy'])->name('employees.destroy')->where('employee', '[0-9]+');

// Employee Contacts
Route::prefix('employees/{employee}')->group(function () {
    Route::get('contacts', [EmployeesContactController::class, 'index']);
    Route::post('contacts', [EmployeesContactController::class, 'store']);
    Route::put('contacts/{contact}', [EmployeesContactController::class, 'update']);
    Route::get('files', [EmployeeFileController::class, 'index']);
    Route::post('files', [EmployeeFileController::class, 'store']);
    Route::get('files-json', [EmployeeFileController::class, 'filesJson']);
});
Route::delete('employee-contacts/delete-multiple', [EmployeesContactController::class, 'deleteMultiple'])->name('employee-contacts.delete-multiple');
Route::delete('employee-contacts/{employee_contact}', [EmployeesContactController::class, 'destroy'])->name('employee-contacts.destroy');

// Employee Files
Route::get('employees/files/{employeeFile}/download', [EmployeeFileController::class, 'download']);
Route::post('employees/files/download-multiple', [EmployeeFileController::class, 'downloadMultipleFiles']);
Route::delete('employees/files/{employeeFile}', [EmployeeFileController::class, 'destroy']);
Route::post('employees/files/delete-multiple', [EmployeeFileController::class, 'destroyMultiple']);
Route::get('/employees/files/{file}/view', [EmployeeFileController::class, 'viewEmployeeFile'])->name('employees.files.view');


// ======================= Tests =======================
Route::resource('tests', TestController::class);
Route::post('/tests/delete-multiple', [TestController::class, 'deleteMultiple']);
Route::post('/uncertainty-history', [UncertaintyController::class, 'store'])->name('uncertainty-history.store');
Route::delete('/uncertainty-history/{id}', [UncertaintyController::class, 'destroy'])->name('uncertainty-history.destroy');
Route::get('/uncertainty-history/{testId}', [UncertaintyController::class, 'index'])->name('uncertainty-history.index');

// Test Files
Route::get('/tests/{testId}/files', [TestFileController::class, 'files'])->name('tests.files');
Route::post('/tests/{test}/files', [TestFileController::class, 'store'])->name('tests.files.store');
Route::get('/tests/{test}/files-json', [TestFileController::class, 'filesJson'])->name('tests.files.json');
Route::get('/tests/files/{file}/view', [TestFileController::class, 'view'])->name('tests.files.view');
Route::get('/tests/files/{file}/download', [TestFileController::class, 'download'])->name('tests.files.download');
Route::delete('/tests/files/{file}', [TestFileController::class, 'destroy'])->name('tests.files.destroy');


// ======================= Equipment =======================
Route::resource('equipments', EquipmentController::class);
Route::post('/equipments/bulk-delete', [EquipmentController::class, 'deleteMultiple']);
Route::post('/equipments/{id}/uncertainty-history', [EquipmentController::class, 'addUncertainty']);

// Equipment Files
Route::get('/equipments/{equipment}/files', [EquipmentFileController::class, 'index']);
Route::post('/equipments/{equipment}/files', [EquipmentFileController::class, 'store']);
Route::delete('/equipments/files/{id}', [EquipmentFileController::class, 'destroy']);
Route::get('/equipments/{equipment}/files-json', [EquipmentFileController::class, 'filesJson']);
Route::get('/equipments/files/{file}/view', [EquipmentFileController::class, 'viewEquipmentFile']);
Route::get('/equipments/files/{file}/download', [EquipmentFileController::class, 'download']);

// Calibration & Maintenance
Route::get('/equipments/{equipment}/calibrations', [CalibrationController::class, 'index']);
Route::post('/equipments/{equipment}/calibrations', [CalibrationController::class, 'store']);
Route::put('/calibrations/{id}', [CalibrationController::class, 'update']);
Route::get('/equipments/{equipmentId}/calibration', [CalibrationController::class, 'showByEquipment']);

Route::post('/equipments/{equipment}/maintenances', [MaintenanceController::class, 'store']);
Route::put('/maintenances/{id}', [MaintenanceController::class, 'update']);


// ======================= Reminders =======================
Route::get('/dashboard/reminders', [ReminderController::class, 'index']);


// ======================= Report Approval =======================
Route::prefix('report-approval')->group(function () {
    Route::get('/', [ReportApprovalController::class, 'index'])->name('report-approval.index');
    Route::post('/approve/{id}', [ReportApprovalController::class, 'approve'])->name('report-approval.approve');
    Route::post('/reject/{id}', [ReportApprovalController::class, 'reject'])->name('report-approval.reject');
    Route::get('/{id}', [ReportApprovalController::class, 'show'])->name('report-approval.show');
});


// ======================= Deliveries =======================
Route::resource('deliveries', DeliveryController::class);
Route::get('/projects-with-relations', [DeliveryController::class, 'getProjectsWithRelations']);
Route::post('/deliveries/{delivery}/approve', [DeliveryController::class, 'approvedeL'])->name('deliveries.approve');
Route::post('/deliveries/{delivery}/send-to-customer', [DeliveryController::class, 'sendToCustomer'])->name('deliveries.send');


// ======================= Confirmations =======================
Route::resource('confirmations', ConfirmationController::class);
Route::post('/confirmation-lines', [ConfirmationLineController::class, 'store'])->name('confirmation-lines.store');
Route::get('/confirmations/{id}/lines', [ConfirmationController::class, 'getLines']);
Route::put('/confirmation-lines/{id}', [ConfirmationLineController::class, 'update'])->name('confirmation-lines.update');
Route::delete('/confirmation-lines/{id}', [ConfirmationLineController::class, 'destroy'])->name('confirmation-lines.destroy');
Route::post('/confirmations/duplicate', [ConfirmationController::class, 'duplicate'])->name('confirmations.duplicate');
Route::delete('/confirmations/{id}', [ConfirmationController::class, 'destroy'])->name('confirmations.destroy');

// Confirmation Files
Route::get('confirmations/{id}/files-json', [ConfirmationFileController::class, 'filesJson']);
Route::post('confirmations/{id}/files', [ConfirmationFileController::class, 'store']);
Route::get('confirmations/files/view/{id}', [ConfirmationFileController::class, 'viewFile']);
Route::get('confirmations/files/download/{id}', [ConfirmationFileController::class, 'download']);
Route::delete('confirmations/files/{id}', [ConfirmationFileController::class, 'destroy']);


// ======================= Financial =======================
Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
