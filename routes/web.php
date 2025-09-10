<?php

use App\Http\Controllers\Backend\TransaksiController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin/register', 'BerandaController@register')->name('admin-register');
Route::post('/admin/register/store', 'BerandaController@registerStore')->name('admin-register-store');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('roles', 'Backend\RolesController', ['names' => 'admin.roles']);
    Route::resource('users', 'Backend\UsersController', ['names' => 'admin.users']);
    Route::resource('admins', 'Backend\AdminsController', ['names' => 'admin.admins']);

    Route::get('tcall', 'Backend\AdminsController@tcall')->name('tcall');
    Route::get('transaksi', 'Backend\TransaksiController@index')->name('transaksi');

    Route::post('/midtrans/token', [TransaksiController::class, 'getSnapToken'])->name('midtrans.token');
    Route::post('/booking/checkout', [TransaksiController::class, 'store'])->name('booking.store');

    Route::group(['prefix' => 'topic'], function () {
        Route::get('/', 'Backend\TopicController@index')->name('topic');
        Route::get('create', 'Backend\TopicController@create')->name('topic.create');
        Route::post('store', 'Backend\TopicController@store')->name('topic.store');
        Route::get('edit/{id}', 'Backend\TopicController@edit')->name('topic.edit');
        Route::post('update/{id}', 'Backend\TopicController@update')->name('topic.update');
        Route::get('destroy/{id}', 'Backend\TopicController@destroy')->name('topic.destroy');
    });

    Route::group(['prefix' => 'article'], function () {
        Route::get('/', 'Backend\ArticleController@index')->name('article');
        Route::get('/search-article', 'Backend\ArticleController@indexArticle')->name('search-article');
        Route::get('/proses-search-article', 'Backend\ArticleController@search')->name('proses-search-article');
        Route::get('/call', 'Backend\ArticleController@indexArticle')->name('call');

        Route::get('create', 'Backend\ArticleController@create')->name('article.create');
        Route::post('store', 'Backend\ArticleController@store')->name('article.store');
        Route::get('edit/{id}', 'Backend\ArticleController@edit')->name('article.edit');
        Route::post('update/{id}', 'Backend\ArticleController@update')->name('article.update');
        Route::get('destroy/{id}', 'Backend\ArticleController@destroy')->name('article.destroy');
    });


    // Login Routes
    Route::get('/login', 'Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login/submit', 'Backend\Auth\LoginController@login')->name('admin.login.submit');

    // Logout Routes
    Route::post('/logout/submit', 'Backend\Auth\LoginController@logout')->name('admin.logout.submit');

    // Forget Password Routes
    Route::get('/password/reset', 'Backend\Auth\ForgetPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset/submit', 'Backend\Auth\ForgetPasswordController@reset')->name('admin.password.update');
});
