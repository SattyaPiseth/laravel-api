<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/register', [AuthenticationController::class, 'register']);
Route::post('auth/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('auth/profile', [AuthenticationController::class, 'profile']);
    Route::post('auth/logout', [AuthenticationController::class, 'logout']);
});

// API Routes for User middleware

Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});

Route::post('roles', [RoleController::class, 'store']);
Route::put('roles/{id}', [RoleController::class, 'update']);

Route::middleware(['auth:api'])->group(function(){
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
});

Route::middleware(['auth:api'])->group(function(){
    Route::get('category', [categoryController::class, 'index']);
    Route::post('category', [categoryController::class, 'store']);
    Route::put('category/{id}', [categoryController::class, 'update']);
    Route::get('category/{id}', [categoryController::class, 'show']);
    Route::delete('category/{id}', [categoryController::class, 'delete']);
});


// about files api routes
Route::get('files', [FileController::class, 'index']);
Route::post('files', [FileController::class, 'store_single']);
Route::post('files/multiple', [FileController::class, 'store_multiple']);
Route::get('files/{id}', [FileController::class, 'show']);
Route::delete('files/{id}', [FileController::class, 'delete']);
Route::delete('files', [FileController::class, 'deleteAll']);
