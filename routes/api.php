<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\StudentController;
 

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/gender/search', [GenderController::class, 'search']);
Route::get('/student/search', [StudentController::class, 'search']);

// Hanya user yang sudah login yang bisa mengakses route ini
Route::middleware('auth:sanctum')->group(function () {

    // API untuk Logout
    Route::post('logout', [AuthController::class, 'logout']);
    
    // API untuk Gender (hanya bisa diakses oleh yang login)
    Route::get('gender', [GenderController::class, 'index']);        
    Route::post('gender', [GenderController::class, 'store']);       
    Route::get('gender/{id}', [GenderController::class, 'show']);    
    Route::put('gender/{id}', [GenderController::class, 'update']);  
    Route::delete('gender/{id}', [GenderController::class, 'destroy']);

    // API untuk Mahasiswa (hanya bisa diakses oleh yang login)
    Route::get('student', [StudentController::class, 'index']);
    Route::post('student', [StudentController::class, 'store']);
    Route::get('student/{id}', [StudentController::class, 'show']);
    Route::put('student/{id}', [StudentController::class, 'update']);
    Route::delete('student/{id}', [StudentController::class, 'destroy']);



});