<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'authorization'], function(){
    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::resource('/files', FileController::class)->middleware('fileUpload');
    Route::match(['get', 'post'],'/download/{id}', [DownloadController::class, 'getFile'])->name('download');
    Route::get('/personal-cabinet', [UserController::class, 'personalCabinet'])->name('personalCabinet');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/file/{file}', [FileController::class, 'show'])->name('file');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    
    Route::group(['middleware' => 'administratorAccess'], function(){
        Route::get('/search-administrator', [SearchController::class, 'searchAdmin'])->name('search.admin');
        Route::resource('/users',  UserController::class);
        Route::match(['get', 'post'],'/users/{id}', [UserController::class, 'activation'])->name('user.activation');
        Route::get('/option',  [OptionController::class, 'index'])->name('option.index');
        Route::post('/option',  [OptionController::class, 'update'])->name('option.update');
    });    
});

Route::group(['middleware' => 'option'], function(){
    Route::get('/registration', [UserController::class, 'registration'])->name('registration');
    Route::post('/registration', [UserController::class, 'store'])->name('registration.store');
});

Route::match(['get', 'post'],'/download-shared-access/{id}', [DownloadController::class, 'getSharedAccessFile'])->name('download.SharedAccess');
Route::get('/shared-access-file/{file}', [FileController::class, 'sharedAccessFile'])->name('file.sharedAccess');
Route::get('/login', [UserController::class, 'loginForm'])->name('login.create');
Route::post('/login', [UserController::class, 'login'])->name('login');




