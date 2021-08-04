<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IpnController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin;
use App\Http\Controllers\FireBaseController;

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
Route::get('admin/customers/bills',[AdminController::class,'showCustomerBills'])->name('admin.customers.bills');
Route::get('admin/profile',[AdminController::class,'profile'])->name('admin.profile');
Route::post('admin/update/profile',[AdminController::class,'updateProfile'])->name('admin.update.profile');
Route::post('admin/update/password',[AdminController::class,'updatePassword'])->name('admin.update.password');
Route::post('admin/bills/generation',[AdminController::class,'generateBills'])->name('admin.bills.generation');

Route::get('customer/initiate/payment',[CustomerController::class,'initiatePayments'])->name('customer.initiate.payment');
Route::post('customer/initiate/payment/submit',[CustomerController::class,'MakePayment'])->name('customer.initiate.payment.submit');
Route::get('customer/payment/translation',[CustomerController::class,'showTransaction'])->name('customer.payment.translation');
Route::get('customer/wallet',[CustomerController::class,'wallet'])->name('customer.wallet');
Route::post('customer/wallet/deposit',[CustomerController::class,'deposit'])->name('customer.wallet.deposit');

Route::get('customer/wallet/deposit/history',[CustomerController::class,'depositHistory'])->name('customer.wallet.deposit.history');
Route::get('customer/wallet/withdraw/history',[CustomerController::class,'withdrawHistory'])->name('customer.wallet.withdraw.history');
Route::get('customer/profile',[CustomerController::class,'userProfile'])->name('customer.profile');
Route::post('customer/profile/update',[CustomerController::class,'updateProfile'])->name('customer.profile.update');
Route::post('customer/password/update',[CustomerController::class,'updatePassword'])->name('customer.password.update');

Route::get('customer/bills',[FireBaseController::class,'getCustomerVolume'])->name('customer.bills');

Route::get('/pesapal-ipn-listener',[IpnController::class,'__invoke'])->name('pesapal.ipn.listener');
