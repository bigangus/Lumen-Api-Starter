<?php

/** @var Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'api'], function() use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
        $router->post('send-sms-code', 'AuthController@sendSmsCode');
        $router->post('forgot-password', 'AuthController@forgotPassword');
    });

    $router->group(['prefix' => 'entity', 'middleware' => ['auth:api', 'acl']], function () use ($router) {
        $router->post('list', 'EntityController@list');
    });

    $router->group(['prefix' => 'user', 'middleware' => ['auth:api']], function () use ($router) {
        $router->post('me', 'UserController@me');
    });

    $router->group(['prefix' => 'user', 'middleware' => ['auth:api', 'acl']], function () use ($router) {
        $router->post('create', 'UserController@create');
        $router->post('disable', 'UserController@disable');
        $router->post('update', 'UserController@update');
        $router->post('assign-role', 'UserController@assignRole');
    });

    $router->group(['prefix' => 'role', 'middleware' => ['auth:api', 'acl']], function () use ($router) {
        $router->post('create', 'RoleController@create');
        $router->post('update', 'RoleController@update');
        $router->post('delete', 'RoleController@delete');
    });

    $router->group(['prefix' => 'permission', 'middleware' => ['auth:api', 'acl']], function () use ($router) {
        $router->post('list', 'PermissionController@list');
    });
});
