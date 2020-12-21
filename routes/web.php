<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', 'HomeController@index');
$router->get('/login', 'HomeController@login');
$router->post('/auth', 'HomeController@auth');
$router->get('/register', 'HomeController@register');
$router->post('/add', 'HomeController@add');
$router->post('/sales', 'HomeController@showSales');

$router->group(['prefix' => 'api', 'middleware' => 'autenticador'], function () use ($router) {

    $router->group(['prefix' => 'sales'], function () use ($router) {
        $router->post('', 'SalesController@store');
    });
    
});

// Auth
$router->post('/api/register', 'TokenController@criarUsuario');
$router->post('/api/login', 'TokenController@gerarToken');