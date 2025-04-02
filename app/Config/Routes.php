<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// habilita las rutas REST de productos
$routes->resource('producto', ['controller' => 'ProductoController']);
