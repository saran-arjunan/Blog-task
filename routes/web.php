<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

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

Route::middleware(['auth'])->group(function () {

    Route::post('create-User',[AuthController::class,'CreateUser']);

    Route::get('Home',[BlogController::class,'Home']);
    Route::get('/',[BlogController::class,'Home']);
    Route::get('Manage-Blog',[BlogController::class,'BlogList']);
    Route::Post('Update-Blog',[BlogController::class,'Update_Blog']);
    Route::Post('Delete-Blog',[BlogController::class,'Delete_Blog']);
    Route::Post('create-Blog',[BlogController::class,'create_blog']);
    Route::Post('Search-category',[BlogController::class,'Search_category']);
});

Route::get('Login',[AuthController::class,'Login'])->name('login');
Route::get('Register',[AuthController::class,'RegisterForm']);
Route::get('Logout',[AuthController::class,'Logout']);
Route::post('ValidateUser',[AuthController::class,'ValidateUser']);
Route::post('Check-Name-Available',[AuthController::class,'Check_Name_Available']);
