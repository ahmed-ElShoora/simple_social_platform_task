<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\PeopleController;
use App\Http\Controllers\web\FriendController;
use App\Http\Controllers\web\PostController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard',[DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::resource('/posts', PostController::class)->except(['show']);
    Route::get('/posts/{post}/likes', [PostController::class, 'likes'])->name('posts.likes');
    Route::get('/posts/{post}/comments', [PostController::class, 'comments'])->name('posts.comments');

    Route::get('/people', [PeopleController::class, 'index'])->name('ui.peoples.index');
    Route::post('/people/search', [PeopleController::class, 'query'])->name('ui.peoples.query');
    Route::get('/people/{user}/connect', [PeopleController::class, 'connect'])->name('ui.peoples.connect');

    Route::get('/friends', [FriendController::class, 'index'])->name('ui.friends.index');
    Route::get('/friends/{user}/accept', [FriendController::class, 'accept'])->name('ui.friends.accept');
    Route::get('/friends/{user}/reject', [FriendController::class, 'reject'])->name('ui.friends.reject');
});

require __DIR__.'/auth.php';
