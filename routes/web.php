<?php

/** @var Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'api'], function() use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
    });

    $router->group(['prefix' => 'user', 'middleware' => ['auth:api']], function () use ($router) {
        $router->post('me', 'UserController@me');
    });
});
