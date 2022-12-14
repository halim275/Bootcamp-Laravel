<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\XenditController;
use Illuminate\Support\Facades\Route;

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


Route::get('/run-migrate', function () {
    return Artisan::call('migrate');
});

Route::get('/run-storage', function () {
    return Artisan::call('storage:link');
});

Route::get('/run-seed', function () {
    return Artisan::call('db:seeder', [
        '--class' => 'PageTableSeeder'
    ]);
});

Route::controller(HomeController::class)->prefix('bootcamp')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('bootcamps');
    Route::get('/{bootcampID}', 'checkout')->name('checkout');
    Route::post('/{bootcampID}', 'actCheckout')->name('actCheckout');
    // Route::post('/{bootcampID}', 'invoice')->name('invoice');
    Route::get('/transaction/{bootcampTransactionID}', 'detail')->name('detail');
});

Auth::routes();

Route::controller(XenditController::class)->group(function () {
    Route::post('/xendit-callback', 'xenditCallback');
});
