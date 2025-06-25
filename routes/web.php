<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\JenisLaundryController;
use App\Http\Controllers\OrderanController;
use App\Http\Controllers\OrderanOnlineController;
use App\Http\Controllers\PaketLaundryController;
use App\Http\Controllers\PaketMemberController;
use App\Http\Controllers\RiwayatCucian;
use App\Http\Controllers\RiwayatCucianController;
use Illuminate\Support\Facades\Route;

// GUEST 
// =================================================
// landing page 
Route::get('/', [GuestController::class, 'index']);

// register
Route::get('/registerMember', [AuthController::class, 'registerMember']);
Route::post('/registerMemberStore', [AuthController::class, 'registerMemberStore']);

// loginMember
Route::get('/loginMember', [AuthController::class, 'loginMember']);
Route::post('/loginMemberAuth', [AuthController::class, 'loginMemberAuth']);

// loginStaff
Route::get('/loginStaff', [AuthController::class, 'loginStaff']);
Route::post('/loginStaffAuth', [AuthController::class, 'loginStaffAuth']);

// loginOwner
Route::get('/loginOwner', [AuthController::class, 'loginOwner']);
Route::post('/loginOwnerAuth', [AuthController::class, 'loginOwnerAuth']);

Route::get('/sendWa', [DashboardController::class, 'sendWa']);

// logout
Route::get('/logout', [AuthController::class, 'logout']);

// status cucian
Route::post('/cekStatusCucian', [GuestController::class, 'cekStatusCucian']);

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
    Route::get('/orderanOffline', [OrderanController::class, 'orderanOffline']);
    Route::post('/orderanOffline', [OrderanController::class, 'orderanOfflineStore']);
    Route::post('/orderanOffline/update', [OrderanController::class, 'orderanOfflineUpdate']);
    Route::post('/orderanOffline/destroy/{id}', [OrderanController::class, 'orderanOfflineDestroy']);
    Route::post('/orderanOffline/bayarCash/{id}', [OrderanController::class, 'orderanOfflineBayarCash']);
    Route::post('/orderanOffline/cucianSelesai/{id}', [OrderanController::class, 'orderanOfflineCucianSelesai']);
    Route::post('/orderanOffline/cucianDiambil/{id}', [OrderanController::class, 'orderanOfflineCucianDiambil']);
    Route::get('/orderanOffline/bayar/success/{id}', [OrderanController::class, 'orderanOfflineBayarSuccess']);
    Route::get('/orderanOffline/cetakNota/{id}', [OrderanController::class, 'orderanOfflineCetakNota']);

    // orderan online
    Route::get('/orderanOnline', [OrderanController::class, 'orderanOnline']);
    Route::post('/orderanOnline/ambilCucian/{id}', [OrderanController::class, 'orderanOnlineAmbilCucian']);
    Route::post('/orderanOnline/inputTimbangan', [OrderanController::class, 'orderanOnlineInputTimbangan']);
    Route::post('/orderanOnline/cuciSelesai/{id}', [OrderanController::class, 'orderanOnlineCuciSelesai']);
    Route::post('/orderanOnline/antarCucian/{id}', [OrderanController::class, 'orderanOnlineAntarCucian']);
});

// MEMBER
// =================================================
Route::middleware(['member'])->group(function () {
    // paket laundry
    Route::get('/paketLaundryMember', [PaketMemberController::class, 'index']);
    Route::post('/paketLaundryMember', [PaketMemberController::class, 'store']);
    Route::post('/paketLaundryMember/update', [PaketMemberController::class, 'update']);
    Route::post('/paketLaundryMember/destroy/{id}', [PaketMemberController::class, 'destroy']);
    Route::get('/paketLaundryMember/bayar/success/{id}', [PaketMemberController::class, 'bayarSuccess']);

    // order langsung
    Route::get('/orderLangsung', [OrderanOnlineController::class, 'orderLangsung']);
    Route::post('/orderLangsung', [OrderanOnlineController::class, 'orderLangsungStore']);
    Route::post('/orderLangsung/update', [OrderanOnlineController::class, 'orderLangsungUpdate']);
    Route::post('/orderLangsung/destroy/{id}', [OrderanOnlineController::class, 'orderLangsungDestroy']);
    Route::get('/orderLangsung/setLocation/{id}', [OrderanOnlineController::class, 'setLocation']);
    Route::get('/orderLangsung/geocode', [OrderanOnlineController::class, 'search']);
    Route::post('/orderLangsung/updateLocation', [OrderanOnlineController::class, 'updateLocation']);
    Route::post('/orderLangsung/bayarOrderan', [OrderanOnlineController::class, 'bayarOrderan']);
    Route::get('/orderLangsung/bayarOrderan/success/{id}', [OrderanOnlineController::class, 'bayarOrderanSuccess']);
    Route::post('/orderLangsung/selesai/{id}', [OrderanOnlineController::class, 'orderLangsungSelesai']);
    Route::get('/orderLangsung/cetakNota/{id}', [OrderanOnlineController::class, 'orderLangsungCetakNota']);

    // order paket 
    Route::get('/orderPaket', [OrderanOnlineController::class, 'orderPaket']);
    Route::post('/orderPaket', [OrderanOnlineController::class, 'orderPaketStore']);
    Route::post('/orderPaket/update', [OrderanOnlineController::class, 'orderPaketUpdate']);
    Route::post('/orderPaket/destroy/{id}', [OrderanOnlineController::class, 'orderPaketDestroy']);
    Route::get('/orderPaket/setLocation/{id}', [OrderanOnlineController::class, 'setLocationPaket']);
    Route::get('/orderPaket/geocode', [OrderanOnlineController::class, 'searchPaket']);
    Route::post('/orderPaket/updateLocation', [OrderanOnlineController::class, 'updateLocationPaket']);
    Route::post('/orderPaket/selesai/{id}', [OrderanOnlineController::class, 'orderPaketSelesai']);
    Route::get('/orderPaket/cetakNota/{id}', [OrderanOnlineController::class, 'orderPaketCetakNota']);

    // ====================================
    Route::get('/biodata', [BiodataController::class, 'index']);
    Route::post('/biodata/update', [BiodataController::class, 'update']);

    Route::get('/riwayatCucian', [RiwayatCucianController::class, 'index']);
    Route::get('/getDataRiwayatCucian/{kategori}', [RiwayatCucianController::class, 'getDataRiwayatCucian']);
});
