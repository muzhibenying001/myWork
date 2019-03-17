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
##################  2019.03.14
// Route::get('/', 'LoginController@index')->name('login.index');

// Route::post('login', 'LoginController@login')->name('login.login');

// Route::get('welcome', 'LoginController@welcome')->name('login.welcome');
##################  2019.03.14

##################  2019.03.15
#登陆页面
Route::get('login', 'login\LoginController@index')->name('login.index');
#登陆验证
Route::post('login', 'login\LoginController@login')->name('login.index');
#退出登陆
Route::get('logout', 'login\LoginController@logout')->name('login.logout');
#文章列表
Route::get('/', 'ArtController@index')->name('art.index');
Route::get('test', 'TestController@index')->name('test.index');
#文章添加页面
Route::get('add','ArtController@add')->name('art.add');
#文章添加入库
Route::post('add','ArtController@insert')->name('art.add');
#文章修改页面
Route::get('edit/{id}','ArtController@edit')->name('art.edit')->where(['id'=>'\d+']);
#文章修改入库
Route::put('edit/{id}','ArtController@update')->name('art.edit')->where(['id'=>'\d+']);
#文章添加页面
Route::delete('del/{id}','ArtController@del')->name('art.del')->where(['id'=>'\d+']);
#文章查看
Route::get('show/{id}','ArtController@show')->name('art.show')->where(['id'=>'\d+']);
