<?php

use App\Http\Controllers\mpesaController;
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
    return view('mpesa');
});
Route::get('/callback', function () {
    return view('callback');
});
Route::get("/pay",[mpesaController::class,'passcodegen']);
Route::post('/new',[mpesaController::class,'newAccessToken']);
Route::get('/stk',[mpesaController::class,'stkpush']);