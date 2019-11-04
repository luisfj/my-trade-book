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

    Route::resource('posts', 'Posts\PostController');
    Route::get('notifications', 'Notifications\NotificationController@notifications')->name('notifications');
    Route::put('notification-read', 'Notifications\NotificationController@markAsRead');
    Route::put('notification-read-all', 'Notifications\NotificationController@markAllAsRead');

});
