<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::loginPage' , ['filter' => 'logged']);
$routes->get('/logout', 'AuthController::logout');
$routes->post('/login', 'AuthController::login' , ['filter' => 'logged']);

$routes->group('client' , ['filter' => 'auth'] , function ($routes) {
    $routes->get('' , 'ClientController::index');
});
