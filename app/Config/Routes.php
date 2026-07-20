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
    $routes->get('operation' , 'ClientController::operationPage');
    $routes->post('operation' , 'ClientController::operation');
});

//action operateur 
$routes->get('/operateur/situation_compte_client', 'ClientController::showSituationCompte');

//show gain
$routes->get('/operateur/gain_par_frais_operation','FraisController::showGainPerOperation');

//crud prefix
$routes->group('operateur/prefixes', function($routes) {
    $routes->get('/', 'NumPrefixeValableController::list');
    $routes->post('create', 'NumPrefixeValableController::create');
    $routes->get('edit/(:num)', 'NumPrefixeValableController::modification/$1');
    $routes->post('update/(:num)', 'NumPrefixeValableController::update/$1');
    $routes->get('delete/(:num)', 'NumPrefixeValableController::delete/$1');
});

//crud route
$routes->group('operateur/operations', function($routes) {
    $routes->get('/', 'OperationController::list');
    $routes->post('create', 'OperationController::create');
    $routes->get('edit/(:num)', 'OperationController::modification/$1');
    $routes->post('update/(:num)', 'OperationController::update/$1');
    $routes->get('delete/(:num)', 'OperationController::delete/$1');
});

//crud route
$routes->group('operateur/frais', function($routes) {
    $routes->get('/', 'FraisController::list');
    $routes->get('gains', 'FraisController::showGainPerOperation');
    $routes->post('create', 'FraisController::create');
    $routes->get('edit/(:num)', 'FraisController::modification/$1');
    $routes->post('update/(:num)', 'FraisController::update/$1');
    $routes->get('delete/(:num)', 'FraisController::delete/$1');
});