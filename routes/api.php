<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Postcontroller;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout',[AuthenticationController::class, 'logout']);
    Route::get('/me',[AuthenticationController::class, 'me']);
    Route::post('/posts',[Postcontroller::class, 'store']);
    Route::patch('/posts/{id}',[Postcontroller::class, 'update'])->middleware('pemilik-postingan');
    Route::delete('/posts/{id}',[Postcontroller::class, 'destroy'])->middleware('pemilik-postingan');
    
    Route::post('/comment',[CommentController::class, 'store']);
    Route::patch('/comment/{id}',[CommentController::class, 'update'])->middleware('pemilik-komentar');
    Route::delete('/comment/{id}',[CommentController::class, 'destroy'])->middleware('pemilik-komentar');
});

Route::get('/posts',[Postcontroller::class, 'index']);
Route::get('/posts/{id}',[Postcontroller::class, 'show']);
Route::get('/posts2/{id}',[Postcontroller::class, 'show2']);



Route::post('/login',[AuthenticationController::class, 'login']);
Route::post('/register',[AuthenticationController::class, 'register']);
Route::post('/password/forgot',[AuthenticationController::class, 'forgotPassword']);
Route::post('/password/reset',[AuthenticationController::class, 'resetPassword']);

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
