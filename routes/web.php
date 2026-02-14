<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\DeliveryChallanController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->name('admin.')->group(function() {
    Route::middleware(['guest'])->group(function() {
        Route::controller(AuthController::class)->group(function() {
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');
        });
    });

    Route::middleware(['auth'])->group(function() {
        Route::controller(AdminController::class)->group(function() {
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::post('/logout', 'logoutHandler')->name('logout');
        });

        Route::controller(ClientController::class)->group(function() {
            Route::get('/all-clients', 'index')->name('all-clients');
            Route::get('/add-clients', 'create')->name('add-clients');
            Route::post('/store-client', 'storeCLient')->name('store-client');
            Route::get('/edit-client', 'editClient')->name('edit-client');
            Route::post('/update-client', 'updateClient')->name('update-client');
            Route::post('/delete-client', 'deleteClient')->name('delete-client');
        });
        
        Route::controller(InvoiceController::class)->group(function() {
            Route::get('/all-invoices', 'index')->name('all-invoices');
            Route::get('/add-invoice', 'create')->name('add-invoice');
            Route::post('/store-invoice', 'storeInvoice')->name('store-invoice');
            Route::get('/invoice/{id}/download', 'downloadPDF')->name('invoice-download');
            Route::get('/invoice/{id}/view', 'viewPDF')->name('invoice-view');
            Route::get('/edit-invoice', 'editInvoice')->name('edit-invoice');
            Route::post('/update-invoice', 'updateInvoice')->name('update-invoice');
            Route::post('/invoices/update-payment', 'updateInvoicePayment')->name('update-invoice-payment');
            Route::post('/invoices/update-status', 'updateInvoiceStatus')->name('update-invoice-status');
            Route::post('/delete-invoice/{id}', 'deleteInvoice')->name('delete-invoice');
            Route::post('/email-invoice', 'emailInvoice')->name('email-invoice');
        });

        Route::controller(PaymentHistoryController::class)->group(function() {
            Route::get('/all-payment-history', 'index')->name('all-payment-history');

        });

        Route::controller(DeliveryChallanController::class)->group(function() {
            Route::get('/all-challans', 'index')->name('all-challans');
            Route::get('/add-delivery-challan', 'create')->name('add-delivery-challan');
            Route::post('/store-delivery-challan', 'storeChallan')->name('store-delivery-challan');
            Route::get('/delivery-challan/{id}/download', 'downloadPDF')->name('download-delivery-challan');
            Route::get('/delivery-challan/{id}/view', 'viewPDF')->name('delivery-challan-view');
            Route::get('/edit-delivery-challan/{id}', 'editDelievryChallan')->name('edit-delivery-challan');
            Route::post('/update-delivery-challan/{id}', 'updateDeliveryChallan')->name('update-delivery-challan');
            Route::post('/delete-delivery-challan/{id}', 'destroy')->name('delete-delivery-challan');
            Route::post('/email-delivery-challan', 'emailDeliveryChallan')->name('email-delivery-challan');
        });

        Route::controller(SettingController::class)->group(function() {
            Route::get('/settings', 'index')->name('settings');
            Route::get('/add-setting', 'create')->name('add-setting');
            Route::post('/store-setting', 'storeSetting')->name('store-setting');
            Route::get('/edit-setting', 'editSetting')->name('edit-setting');
            Route::post('/update-setting', 'updateSetting')->name('update-setting');
        });
    });
});

// Route::get('/test-pdf', function () {
//     $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadHTML('<h1>Hello World!</h1>')
//         ->setOption('enable-local-file-access', true);
    
//     return $pdf->inline('test.pdf');
// });


