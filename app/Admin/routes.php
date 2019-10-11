<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/wechat/tag', 'TagController@tag_list')->name('admin.tag');
    $router->get('/wechat/add_tag', 'TagController@add_tag')->name('admin.add_tag');
    $router->get('/wechat/del_tag', 'TagController@del_tag')->name('admin.del_tag');

});
