<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
// use App\Http\Controllers\PostController;
// use App\Http\Controllers\LikeController;
// use App\Http\Controllers\CommentController;
//


// // مسارات المنشورات
// Route::get('/posts', [PostController::class, 'index']);
// Route::post('/posts', [PostController::class, 'store']);
// Route::get('/posts/{id}', [PostController::class, 'show']);
// Route::put('/posts/{id}', [PostController::class, 'update']);
// Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// // مسارات الإعجابات
// Route::post('/posts/{postId}/likes', [LikeController::class, 'toggleLike']);

// // مسارات التعليقات
// Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);
// Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
// Route::put('/comments/{id}', [CommentController::class, 'update']);
// Route::delete('/comments/{id}', [CommentController::class, 'destroy']);; -->
