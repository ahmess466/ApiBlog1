<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostController;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class , 'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/all/posts',[PostController::class,'getAllPosts']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    //blog Api
    Route::post('/add/post',[PostController::class,'addNewPost']);
    Route::get('/post/{id}',[PostController::class,'getPostById']);
    Route::post('/update/post/{id}',[PostController::class,'updatePost']);
    Route::delete('/delete/post/{id}',[PostController::class,'deletePost']);

    //Comment Api
    Route::post('/comment', [CommentController::class, 'postComment']);
    //Like Api
    Route::post('/like', [LikesController::class, 'likePost']);

});


