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
    Route::post('userUpdateRole', 'Admin\UsersController@updateRole')->name('users.update.role');
});

Route::middleware('admin')->prefix('ad')->group(function () {
    Route::get('users', 'Admin\UsersController@index')->name('users.index');

    Route::get('perfis', 'Admin\PerfilInvestidorController@index')->name('perfil.index');
    Route::get('perfil-edit/{id}', 'Admin\PerfilInvestidorController@edit')->name('perfil.edit');
    Route::get('perfil', 'Admin\PerfilInvestidorController@add')->name('perfil.add');
    Route::put('perfil-edit/{id}', 'Admin\PerfilInvestidorController@update')->name('perfil.update');
    Route::post('perfil', 'Admin\PerfilInvestidorController@create')->name('perfil.create');
    Route::delete('perfil/{id}', 'Admin\PerfilInvestidorController@delete')->name('perfil.delete');

    Route::get('posts', 'Posts\PostController@index')->name('posts.index');
    Route::get('post-show/{id}', 'Posts\PostController@show')->name('posts.show');
    Route::put('post-show/{id}', 'Posts\PostController@update')->name('posts.update');
    Route::get('post', function () { return view('modulos.admin.adicionarPostEnquete'); })->name('posts.add');
    Route::post('post', 'Posts\PostController@create')->name('posts.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('comment', 'Posts\CommentController@store')->name('comment.store');
    Route::get('profile', 'Configuracoes\ProfileController@index')->name('profile.index');
    Route::put('profile', 'Configuracoes\ProfileController@update')->name('profile.update');
    Route::get('configuracoes', 'Configuracoes\ConfiguracoesController@index')->name('configuracoes.index');
    Route::put('configuracoes', 'Configuracoes\ConfiguracoesController@update')->name('configuracoes.update');

    Route::get('painel-notificacoes', 'Notifications\NotificationController@painelNotificacoes')->name('notifications.panel');
    Route::get('notifications', 'Notifications\NotificationController@notifications')->name('notifications');
    Route::put('notification-read', 'Notifications\NotificationController@markAsRead');
    Route::put('notification-read-all', 'Notifications\NotificationController@markAllAsRead');
    Route::get('notification/post/{idnotification}', 'Notifications\NotificationController@getPostFromNotification');
    Route::put('enquete/votar', 'Posts\VotacaoController@votarEnquete');

    Route::get('bugs', 'Bugs\BugController@index')->name('bugs');
    Route::get('bug-show', 'Bugs\BugController@show')->name('bug.show');
    Route::post('bugs', 'Bugs\BugController@store')->name('bug.store');
    Route::post('bug-message', 'Bugs\BugController@addMessage')->name('bug.addmessage');
    Route::post('bug-verificado', 'Bugs\BugController@marcarComoVerificada')->name('bug.verificado');

    Route::get('painel-comunicacao', 'Comunicacao\ComunicaoController@index')->name('comunicacao.index');

});
