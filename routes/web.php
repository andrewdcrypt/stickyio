<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\checkout;
use App\Http\Controllers\upsell;
use App\Http\Controllers\summary;

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

Route::get('/checkout', [checkout::class, 'pageView'])->name('checkout');
Route::get('/paypal', [checkout::class, 'paypalView'])->name('paypal');
Route::any('/upsell/{pg?}', [upsell::class, 'pageView'])->name('upsell');
Route::get('/receipt', [summary::class, 'pageView'])->name('receipt');

Route::post('/checkout/checkout', [checkout::class, 'processCheckout'])->name('processCheckout');
Route::post('/upsell/checkout', [upsell::class, 'processUpsell'])->name('processUpsell');