<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComentarioController;

Route::get('/', HomeController::class)->name('home')->middleware('auth');

//Registro
Route::get('/register',[RegisterController::class, 'index'])->name('register');
Route::post('/register',[RegisterController::class, 'store']);

// Iniciar sesion
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Rutas para el perfil
Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index')->middleware('auth');
Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store')->middleware('auth');

// Cerrar Sesion
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

//Dashboard
Route::get('/{user:username}', [PostController::class, 'index'])->name('posts.index'); //->middleware('auth'); //Una forma de usar los middleware
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create'); //->middleware('auth');
Route::post('/posts' , [PostController::class, 'store'])->name('posts.store'); //->middleware('auth');
Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Comentarios
Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store');

//Imagenes
Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');

// Like a las fotos
Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('posts.likes.store');
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('posts.likes.destroy');


// Siguiendo usuarios
Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->name('users.follow'); // Seguir usuario
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->name('users.unfollow'); //Dejar de seguir
