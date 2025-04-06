<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Agrupar todas las rutas bajo /api
$routes->group('api', function ($routes) {

    // Rutas de productos
    $routes->resource('producto', ['controller' => 'ProductoController']);
    $routes->options('producto', 'ProductoController::optionsHandler');
    $routes->options('producto/(:segment)', 'ProductoController::optionsHandler/$1');

    // Rutas de autenticación
    $routes->post('register', 'AuthController::register');
    $routes->options('register', 'AuthController::optionsHandler');
    $routes->post('login', 'AuthController::login');
    $routes->options('login', 'AuthController::optionsHandler');
    $routes->get('logout', 'AuthController::logout');
    $routes->options('logout', 'AuthController::optionsHandler');
    $routes->get('usuario-actual', 'AuthController::usuarioActual');
    $routes->options('usuario-actual', 'AuthController::optionsHandler');

    // Rutas de pedidos
    $routes->resource('pedido');
    $routes->options('pedido', 'Pedido::optionsHandler');
    $routes->get('mis-pedidos', 'Pedido::misPedidos');
    $routes->options('pedido/(:segment)', 'Pedido::optionsHandler/$1');

    // Rutas administrativas
    $routes->get('admin/usuarios', 'Admin::listUsers');
    $routes->options('admin/usuarios', 'Admin::optionsHandler');
    $routes->put('admin/usuarios/(:num)/rol', 'Admin::setRole/$1');
    $routes->options('admin/usuarios/(:num)/rol', 'Admin::optionsHandler/$1');

    // Ruta para el chatbot
    $routes->post('chatbot', 'Chatbot::index');
    $routes->options('chatbot', 'Chatbot::optionsHandler');
});