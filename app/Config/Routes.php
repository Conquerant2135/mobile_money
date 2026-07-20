<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::loginPage');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/login', 'AuthController::login');

$routes->group('client' , function ($routes) {
    $routes->get('' , 'ClientController::index');
});