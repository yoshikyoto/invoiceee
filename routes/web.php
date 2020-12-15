<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FreeeAuthController;
use App\Http\Controllers\HerokuAuthController;
use App\Http\Controllers\HerokuInvoiceController;

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

Route::get(
    '/',
    Controller::class . '@index'
)->name('index');

Route::get('/freee/callback', FreeeAuthController::class . '@callback');
Route::get('/heroku/callback', HerokuAuthController::class . '@callback');
Route::get('/invoice/heroku', HerokuInvoiceController::class . '@list');
