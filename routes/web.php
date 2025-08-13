<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourierRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/admin-login', [AdminController::class, 'adminLogin'])->name('custom.login');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');

        /** User Management */
        Route::resource('users', UserController::class);
        Route::resource('wallets', WalletController::class);

        /**User Charges */
        Route::get('user-charges', [UserController::class, 'userCharges'])->name('user-charges');
        Route::patch('users/{user}/update-user-charges', [UserController::class, 'updateUserCharges'])->name('users.update-chargeable-amount');
        Route::post('/users/export/csv', [UserController::class, 'user_exportCsv'])->name('users.export.csv');
        Route::get('list-order', [UserController::class, 'list'])->name('list.order');
        Route::get('/orders/{id}', [UserController::class, 'view'])->name('orders.view');
        Route::post('cancel-order', [UserController::class, 'cancelOrder'])->name('order.cancel');
        Route::post('order-label-data', [UserController::class, 'orderLabelData'])->name('order.label-data');
        Route::post('/orders/export-csv', [UserController::class, 'exportCsv'])->name('orders.export.csv');
        Route::get('pincode', [UserController::class, 'pincode'])->name('pincode');
        Route::post('/upload/pincode', [UserController::class, 'uploadPincode'])->name('upload.pincode');

        Route::get('/users/{id}/wallet', [UserController::class, 'showwallet'])->name('users.wallet');
        Route::put('/users/{id}/wallet', [UserController::class, 'updateWallet'])->name('users.wallet.update');

        Route::patch('/wallets/{wallet}/update-status', [WalletController::class, 'updateStatus'])->name('wallets.updateStatus');
       
        
        Route::get('user-weight-slab/{user_id}', [CourierRateController::class, 'userWeightSlab'])->name('user-weight-slab');
        Route::post('/admin/user-weight-slab/save', [CourierRateController::class, 'saveUserWeightSlab'])->name('save-weight-slabs');
        Route::get('air-courier-rate-slab/{company_id}/{user_id}', [CourierRateController::class, 'airCourierRateSlab'])->name('air-courier-rate-slab');
        Route::get('surface-courier-rate-slab/{company_id}/{user_id}', [CourierRateController::class, 'SurfaceCourierRateSlab'])->name('surface-courier-rate-slab');
        Route::post('/save-air-courier-rates', [CourierRateController::class, 'storeAIRRates'])->name('save.courier.air.rates');
        Route::post('/save-surface-courier-rates', [CourierRateController::class, 'storeSurfaceRates'])->name('save.surface.rates');

    });
});
