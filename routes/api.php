<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectContactController;
use App\Models\PriceList;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

Route::post('/projects/{projectId}/contacts', [ProjectContactController::class, 'store']);
Route::put('/projects/{projectId}/contacts/{contactId}', [ProjectContactController::class, 'update']);

Route::get('/api/projects', function () {
    return App\Models\Project::select('id', 'name')->get();
});



