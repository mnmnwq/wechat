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

Route::get('/wechat/youjia','WechatController@youjia');

Route::get('/wechat/wechat_list','WechatController@wechat_list');
Route::get('wechat/create_qrcode','WechatController@create_qrcode');
Route::get('wechat/get_location','WechatController@get_location');

Route::get('/wechat/menu_list','MenuController@menu_list'); //菜单列表
Route::post('/wechat/create_menu','MenuController@create_menu'); //菜单
Route::get('/wechat/load_menu','MenuController@load_menu'); //刷新菜单


Route::get('/wechat/upload','ResourceController@upload');
Route::post('/wechat/do_upload','ResourceController@do_upload');

Route::get('/wechat/source_list','ResourceController@source_list');
Route::get('/wechat/resource_list','ResourceController@resource_list');
Route::get('/wechat/download','ResourceController@download');

Route::any('/wechat/event','EventController@event');

Route::get('/wechat/login','LoginController@wechat_login');
Route::get('/wechat/code','LoginController@wechat_code');
Route::get('/wechat/index','WechatController@index');
Route::post('/post/test','LoginController@post_test');

//模板消息
Route::get('/wechat/push_template_msg','WechatController@push_template_msg'); //推送模板消息

//标签管理
Route::get('/wechat/tag_list','TagController@tag_list');
Route::get('/wechat/user_tag','TagController@user_tag');//粉丝身上标签
Route::get('/wechat/push_tag_msg','TagController@push_tag_msg');
Route::get('/wechat/add_tag','TagController@add_tag');
Route::post('/wechat/add_user_tag','TagController@add_user_tag'); //给用户打标签
Route::post('/wechat/do_add_tag','TagController@do_add_tag');
Route::get('/wechat/update_tag','TagController@update_tag');
Route::post('/wechat/do_update_tag','TagController@do_update_tag');
Route::get('/wechat/del_tag','TagController@del_tag');
Route::get('/wechat/wechat_user','WechatController@wechat_user');
