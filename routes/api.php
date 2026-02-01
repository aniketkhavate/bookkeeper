<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceEntryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('blogs', BlogController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('dashboard', [AuthController::class, 'dashboard']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->resource('service', ServiceController::class);
Route::middleware('auth:sanctum')->resource('customer', CustomerController::class);
Route::middleware('auth:sanctum')->get('serviceEntry', [ServiceEntryController::class, 'index']);
Route::middleware('auth:sanctum')->post('serviceEntry/store', [ServiceEntryController::class, 'store']);
Route::middleware('auth:sanctum')->put('serviceEntry/update/{id}', [ServiceEntryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('serviceEntry/delete/{id}', [ServiceEntryController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('getRawData', [ServiceEntryController::class, 'getRawData']);
Route::middleware('auth:sanctum')->get('dashboard', [DashboardController::class, 'index']);

# Reports
Route::middleware('auth:sanctum')->get('/report/daily-entries', [ReportController::class, 'dailyEntries']);
Route::middleware('auth:sanctum')->get('/report/customer-wise', [ReportController::class, 'customerWise']);
Route::middleware('auth:sanctum')->get('/report/pending-bills', [ReportController::class, 'pendingBills']);
Route::middleware('auth:sanctum')->get('/report/pdf', [ReportController::class, 'exportPdf']);
