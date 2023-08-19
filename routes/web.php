<?php

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
    return redirect()->route('login');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');;

Route::controller(App\Http\Controllers\GridController::class)->group(function () {
    Route::get('/Grids', 'index')->name('grid')->middleware('verified');
    Route::post('/Grids/Insert','store')->name('grid_store')->middleware('verified');
    Route::post('/Grids/Update', 'update')->name('grid_update')->middleware('verified');
    Route::get('/Grids/Delete', 'delete')->name('grid_delete')->middleware('verified');
});

Route::controller(App\Http\Controllers\LoadController::class)->group(function () {
    Route::get('/Loads', 'index')->name('load')->middleware('verified');
    Route::get('/Loads/LoadDetails', 'getLoadDetails')->name('load_details')->middleware('verified');
    Route::post('/Loads/Update', 'update')->name('load_update')->middleware('verified');
    Route::get('/Loads/Delete', 'delete')->name('load_delete')->middleware('verified');
});

Route::controller(App\Http\Controllers\ReportsController::class)->group(function () {
    Route::get('Reports/Daily/{report_date?}', 'daily')->name('daily_report');
});
