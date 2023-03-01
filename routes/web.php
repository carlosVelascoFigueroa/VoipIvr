<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\CdrController;
use App\Http\Controllers\DropboxController;
use App\Http\Models\Call;
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

Route::post('/inbound',[CallsController::class, 'savecdr']);
Route::any('/menu',[CallsController::class, 'menu']);
Route::get('/dropbox',[DropboxController::class, 'saveLocal']);
Route::get('/sms',[SmsController::class, 'sms']);
Route::any('/welcome',[CallsController::class, 'welcome']);
ROUTE::GET('/cdr',[CdrController::class, 'cdr']);


