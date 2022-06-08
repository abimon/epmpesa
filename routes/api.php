<?php

use App\Http\Controllers\mpesaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/password',[mpesaController::class, 'passcodegen']);
Route::post('/new',[mpesaController::class,'AccessToken']);
Route::post('/stk',[mpesaController::class,'stkpush']);
