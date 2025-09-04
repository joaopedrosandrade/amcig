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

Route::get('/', 'FrontController@associadoCreate')->name('associado.index');;
Route::post('/associado/store', 'FrontController@associadoStore')->name('associado.store');
Route::get('/associado/success', 'FrontController@associadoSuccess')->name('associado.success');

// Rota pública para carteirinha virtual
Route::get('/carteirinha/{matricula}', 'CarteirinhaController@show')->name('carteirinha.show');
Route::get('/carteirinha/{matricula}/print', 'CarteirinhaController@print')->name('carteirinha.print');

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
        
        // Rotas para gerenciar associados
        Route::prefix('associados')->group(function() {
            Route::get('/', 'Admin\AssociadoController@index')->name('admin.associados.index');
            Route::get('/data', 'Admin\AssociadoController@data')->name('admin.associados.data');
            Route::get('/show', 'Admin\AssociadoController@show')->name('admin.associados.show');
            Route::post('/update-status', 'Admin\AssociadoController@updateStatus')->name('admin.associados.update-status');
        });
    });
});








