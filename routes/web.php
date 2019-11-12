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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('super.admin')->prefix('sa')->group(function () {

});

Route::middleware('admin')->prefix('ad')->group(function () {

});

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('comment', 'Posts\CommentController@store')->name('comment.store');
    Route::get('configuracoes', 'Configuracoes\ConfiguracoesController@index')->name('configuracoes.index');
    Route::put('configuracoes', 'Configuracoes\ConfiguracoesController@update')->name('configuracoes.update');

    Route::resource('posts', 'Posts\PostController');
    Route::get('notifications', 'Notifications\NotificationController@notifications')->name('notifications');
    Route::put('notification-read', 'Notifications\NotificationController@markAsRead');
    Route::put('notification-read-all', 'Notifications\NotificationController@markAllAsRead');

    Route::get('bugs', 'Bugs\BugController@index')->name('bugs');
    Route::get('bug-show', 'Bugs\BugController@show')->name('bug.show');
    Route::post('bugs', 'Bugs\BugController@store')->name('bug.store');
    Route::post('bug-message', 'Bugs\BugController@addMessage')->name('bug.addmessage');
    Route::post('bug-verificado', 'Bugs\BugController@marcarComoVerificada')->name('bug.verificado');

    Route::get('painel-comunicacao', 'Comunicacao\ComunicaoController@index')->name('comunicacao.index');

});
