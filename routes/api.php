<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->resource('service', ServiceController::class);
Route::middleware('auth:sanctum')->resource('customer', CustomerController::class);
Route::middleware('auth:sanctum')->get('dashboard', [AuthController::class, 'dashboard']);
Route::middleware('auth:sanctum')->get('serviceEntry', [ServiceEntryController::class, 'index']);
Route::middleware('auth:sanctum')->post('serviceEntry/store', [ServiceEntryController::class, 'store']);
Route::middleware('auth:sanctum')->get('getRawData', [ServiceEntryController::class, 'getRawData']);
// Reports
// Route::get('reports/daily', [ServiceEntryController::class, 'dailyWorkReport']);
// Route::get('reports/monthly/{month}/{year}', [ServiceEntryController::class, 'monthWiseWorkReport']);
// Route::get('reports/yearly/{year}', [ServiceEntryController::class, 'yearWiseWorkReport']);
// Route::get('reports/customer/daily/{customerId}', [ServiceEntryController::class, 'customerDailyReport']);
// Route::get('reports/customer/monthly/{customerId}/{month}/{year}', [ServiceEntryController::class, 'customerMonthlyReport']);
// Route::get('reports/customer/yearly/{customerId}/{year}', [ServiceEntryController::class, 'customerYearlyReport']);
// Route::get('reports/customer/unpaid/{customerId}', [ServiceEntryController::class, 'customerUnpaidBillsReport']);
