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
    dd(session());
    return view('welcome');
});

Route::get('/login',function(){
    return view('login');
});

Route::get('/wechat/login','LoginController@wechat_login');
Route::get('/wechat/code','LoginController@wechat_code');
Route::get('/wechat/index','WechatController@index');
Route::post('/post/test','LoginController@post_test');

//标签管理
Route::get('/wechat/tag_list','TagController@tag_list');
Route::get('/wechat/add_tag','TagController@add_tag');
Route::post('/wechat/do_add_tag','TagController@do_add_tag');
Route::get('/wechat/update_tag','TagController@update_tag');
Route::post('/wechat/do_update_tag','TagController@do_update_tag');
Route::get('/wechat/del_tag','TagController@del_tag');
