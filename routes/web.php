<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisLaundryController;
use App\Http\Controllers\OrderanController;
use App\Http\Controllers\OrderanOfflineController;
use App\Http\Controllers\OrderanOnlineController;
use App\Http\Controllers\PaketLaundryController;
use App\Models\OrderanOffline;
use App\Models\OrderanOnline;
use Illuminate\Support\Facades\Route;

// GUEST 
// =================================================
// landing page 
Route::get('/', [CustomerController::class, 'index']);

//auth customer
Route::get('/loginCustomer', [AuthController::class, 'loginCustomer']);
Route::get('/registerCustomer', [AuthController::class, 'registerCustomer']);

// loginStaff
Route::get('/loginStaff', [AuthController::class, 'loginStaff']);
Route::post('/loginStaffAuth', [AuthController::class, 'loginStaffAuth']);

// loginOwner
Route::get('/loginOwner', [AuthController::class, 'loginOwner']);
Route::post('/loginOwnerAuth', [AuthController::class, 'loginOwnerAuth']);

// logout
Route::get('/logout', [AuthController::class, 'logout']);

// OWNER
// =================================================
Route::middleware(['owner'])->group(function () {
    // dashboard
    Route::get('/dashboardOwner', [DashboardController::class, 'index']);

    // jenis laundry
    Route::get('/jenisLaundryOwner', [JenisLaundryController::class, 'index']);
    Route::post('/jenisLaundryOwner', [JenisLaundryController::class, 'store']);
    Route::post('/jenisLaundryOwner/update', [JenisLaundryController::class, 'update']);
    Route::post('/jenisLaundryOwner/destroy/{id}', [JenisLaundryController::class, 'destroy']);

    // paket laundry
    Route::get('/paketLaundryOwner', [PaketLaundryController::class, 'index']);
    Route::post('/paketLaundryOwner', [PaketLaundryController::class, 'store']);
    Route::post('/paketLaundryOwner/update', [PaketLaundryController::class, 'update']);
    Route::post('/paketLaundryOwner/destroy/{id}', [PaketLaundryController::class, 'destroy']);
});

// STAFF
// =================================================
Route::middleware(['staff'])->group(function () {
    // orderan offline
    Route::get('/orderanOffline', [OrderanOfflineController::class, 'orderanOffline']);
    Route::post('/orderanOffline', [OrderanOfflineController::class, 'orderanOfflineStore']);
    Route::post('/orderanOffline/update', [OrderanOfflineController::class, 'orderanOfflineUpdate']);
    Route::post('/orderanOffline/destroy/{id}', [OrderanOfflineController::class, 'orderanOfflineDestroy']);
    Route::post('/orderanOffline/bayarCash/{id}', [OrderanOfflineController::class, 'orderanOfflineBayarCash']);
    Route::post('/orderanOffline/cucianSelesai/{id}', [OrderanOfflineController::class, 'orderanOfflineCucianSelesai']);
    Route::post('/orderanOffline/cucianDiambil/{id}', [OrderanOfflineController::class, 'orderanOfflineCucianDiambil']);
    Route::get('/orderanOffline/bayar/success/{id}', [OrderanOfflineController::class, 'orderanOfflineBayarSuccess']);
    Route::get('/orderanOffline/cetakNota/{id}', [OrderanOfflineController::class, 'orderanOfflineCetakNota']);

    // orderan online
    Route::get('/orderanOnline', [OrderanOnlineController::class, 'orderanOnline']);
    Route::post('/orderanOnline/ambilCucian/{id}', [OrderanOnlineController::class, 'orderanOnlineAmbilCucian']);
    Route::post('/orderanOnline/inputTimbangan', [OrderanOnlineController::class, 'orderanOnlineInputTimbangan']);
    Route::post('/orderanOnline/cuciSelesai/{id}', [OrderanOnlineController::class, 'orderanOnlineCuciSelesai']);
    Route::post('/orderanOnline/antarCucian/{id}', [OrderanOnlineController::class, 'orderanOnlineAntarCucian']);
});