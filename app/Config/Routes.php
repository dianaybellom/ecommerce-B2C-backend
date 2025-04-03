<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// habilita las rutas REST de productos
$routes->resource('producto', ['controller' => 'ProductoController']);
$routes->options('producto', 'ProductoController::optionsHandler');
$routes->options('producto/(:segment)', 'ProductoController::optionsHandler/$1');
// habilita las rutas REST de usuarios
$routes->post('register', 'AuthController::register');
$routes->options('register', 'AuthController::optionsHandler');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->options('login', 'AuthController::optionsHandler');
// habilita las rutas RESTful completo para pedidos
$routes->resource('pedido'); 
$routes->get('mis-pedidos', 'Pedido::misPedidos');
// Rutas para actualización de rol
$routes->get('admin/usuarios', 'Admin::listUsers');
$routes->put('admin/usuarios/(:num)/rol', 'Admin::setRole/$1');