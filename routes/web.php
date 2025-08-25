<?php

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

Route::get('/', 'FrontController@associadoCreate');
Route::post('/associado/store', 'FrontController@associadoStore')->name('associado.store');
Route::get('/associado/success', 'FrontController@associadoSuccess')->name('associado.success');

Auth::routes();

// Rotas para associados logados
Route::middleware(['auth'])->group(function () {
    Route::get('/associado/dashboard', 'AssociadoController@dashboard')->name('associado.dashboard');
    Route::get('/associado/profile', 'AssociadoController@profile')->name('associado.profile');
    Route::put('/associado/profile', 'AssociadoController@updateProfile')->name('associado.profile.update');
});

Route::get('/home', 'HomeController@index')->name('home');

// primeiro esse

//    // ==> acrescentar
//    Route::get('/admin/login', 'Auth\AdminLoginController@index')->name('admin.login');
//    Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
//    
//    
//    // ==> troquei de lugar
//    Route::get('/admin', 'AdminController@index')->name('admin.dashboard');


// depois esse
Route::prefix('/admin')->group(function() {
    Route::get('/login', 'Auth\AdminLoginController@index')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    
    // Rotas protegidas por autenticação admin
    Route::middleware(['auth:admin'])->group(function() {
        Route::get('/', 'AdminController@index')->name('admin.dashboard');
        Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    });
});








