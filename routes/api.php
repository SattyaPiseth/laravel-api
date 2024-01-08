<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProductController;
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



Route::middleware(['auth:api'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
});




Route::middleware(['auth:api'])->group(function(){
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
});

// categories api routes
Route::middleware(['auth:api'])->group(function(){
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('category', [CategoryController::class, 'store']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::delete('category/{id}', [CategoryController::class, 'delete']);
});

// files api routes
Route::middleware(['auth:api'])->group(function(){
    Route::get('files', [FileController::class, 'index']);
    Route::post('files', [FileController::class, 'store_single']);
    Route::get('files/{id}', [FileController::class, 'show']);
    Route::delete('files/{id}', [FileController::class, 'delete']);
    Route::delete('files', [FileController::class, 'deleteAll']);
});

// product api routes
Route::middleware(['auth:api'])->group(function(){
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store_single']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::delete('products/{id}', [ProductController::class, 'delete']);
    Route::delete('products', [ProductController::class, 'deleteAll']);
});

Route::middleware(['auth:api'])->group(function(){
//?Brand Controller
    Route::get('brand', [BrandController::class, 'index']);
    Route::post('brand', [BrandController::class, 'create']);
    Route::get('brand/{id}', [BrandController::class, 'show']);
    Route::put('brand/{id}', [BrandController::class, 'update']);
    Route::delete('brand/{id}', [BrandController::class, 'delete']);
});




