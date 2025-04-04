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
$routes->options('login', 'AuthController::optionsHandler');
$routes->get('logout', 'AuthController::logout');
$routes->options('logout', 'AuthController::optionsHandler');
$routes->get('usuario-actual', 'AuthController::usuarioActual');
$routes->get('usuario-actual', 'AuthController::optionsHandler');

// habilita las rutas RESTful completo para pedidos
$routes->resource('pedido'); 
$routes->options('pedido', 'Pedido::optionsHandler');
$routes->get('mis-pedidos', 'Pedido::misPedidos');
$routes->options('pedido/(:segment)', 'Pedido::optionsHandler/$1');

// Rutas para actualización de rol
$routes->get('admin/usuarios', 'Admin::listUsers');
$routes->options('admin/usuarios', 'Admin::optionsHandler');
$routes->put('admin/usuarios/(:num)/rol', 'Admin::setRole/$1');
$routes->options('admin/usuarios/(:num)/rol', 'Admin::optionsHandler/$1');

// Ruta OpenAI
$routes->post('chatbot', 'Chatbot::index');
$routes->options('chatbot', 'Chatbot::optionsHandler');