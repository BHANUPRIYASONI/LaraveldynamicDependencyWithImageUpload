<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DependentController;
use App\Models\Project3;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


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

Route::get('/', [DependentController::class,'index']);

Route::post('/register', [DependentController::class,'register'])->name('add.user');
Route::get('/getCountry', [DependentController::class,'getCountry']);
Route::get('/getState', [DependentController::class,'getState']);
Route::get('/getCity', [DependentController::class,'getCity']);
Route::get('/getData', [DependentController::class,'getData']);
Route::get('/getInfoById', [DependentController::class,'getInfoById']);
Route::post('/deleteData', [DependentController::class,'deleteData']);
Route::post('/nameValidation', [DependentController::class,'nameValidation']);
