<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;

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

Route::get('/post',[PostController::class, 'index']);
Route::get('/post/{id}',[PostController::class, 'show']);

Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/me', [AuthenticationController::class, 'me'])->middleware(['auth:sanctum']);

Route::post('/posts', [PostController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/posts/{id}', [PostController::class, 'update'])->middleware(['idPost', 'auth:sanctum']);
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware(['idPost', 'auth:sanctum']);

Route::post('/comment', [CommentController::class, 'store'])->middleware(['auth:sanctum']);