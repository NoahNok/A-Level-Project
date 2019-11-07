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

use App\Year;
use App\Form;

Route::get('/', function () {
    return view('index');
});

ROute::get('/vue', function() {
  return view('vue');
});

Auth::routes();

Route::get('/test', function(){
   return view('test');
});

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/api/forms/all', 'FormController@all');

Route::get('/api/years/all', 'YearController@all');

Route::get('/api/users/all', 'UserController@all');

// Routes for managing Years and Forms on the backend. Two POSTs for creation and editing and a DELETE when deleting.
Route::post('/api/years/create', 'YearController@create');
Route::post('/api/years/edit/{id}', 'YearController@edit');
Route::delete('/api/year/delete', 'YearController@delete');
Route::get('/api/year/{id}', 'YearController@get');


Route::get('/api/form/{id}', 'FormController@get');
Route::post('/api/forms/create', 'FormController@create');
Route::post('/api/forms/edit/{id}', 'FormController@edit');
Route::delete('/api/form/delete', 'FormController@delete');


Route::post('/api/users/edit/{id}', 'UserController@edit');
Route::get('/api/user/{id}', 'UserController@get');
Route::delete('/api/user/delete', 'UserController@delete');

Route::get('/api/roles', 'UserController@roles');

