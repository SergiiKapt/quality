<?php

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

Route::post('/scripts', 'Api\ScriptController@index');
Route::post('/script/add', 'Api\ScriptController@store');
Route::post('/script/delete', 'Api\ScriptController@destroy');
Route::post('/script/position', 'Api\ScriptController@position');

