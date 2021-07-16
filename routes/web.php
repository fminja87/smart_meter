<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IpnController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::get('admin/login',[Admin\AdminLoginController::class,'adminLogin'])->name('admin.login');
Route::post('admin/auth',[Admin\AdminLoginController::class,'adminAuth'])->name('admin.auth');
Route::post('admin/logout',[Admin\AdminLoginController::class,'destroy'])->name('admin.logout');


Route::get('admin/home',[AdminController::class,'home'])->name('admin.home');
Route::get('admin/customers',[AdminController::class,'showCustomers'])->name('admin.customers');


Route::get('customer/initiate/payment',[CustomerController::class,'initiatePayments'])->name('customer.initiate.payment');
Route::post('customer/initiate/payment/submit',[CustomerController::class,'MakePayment'])->name('customer.initiate.payment.submit');
Route::get('customer/payment/translation',[CustomerController::class,'showTransaction'])->name('customer.payment.translation');


Route::get('/pesapal-ipn-listener',[IpnController::class,'__invoke'])->name('pesapal.ipn.listener');
