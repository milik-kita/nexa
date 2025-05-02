<?php
global $router;

use App\Controllers\UserController;

$router->get('/', function () use ($router) {
    return $router->renderView('home');
});

// User routes
$router->get('/service', function () use ($router) {
    return $router->renderView('users/service');
});
$router->post('/service', function () use ($router) {
    return $router->renderView('users/service');
});

$router->get('/login', function () use ($router) {
    return $router->renderView('login');
});

$router->get('/users', [new UserController(), 'index']);
$router->get('/users/create', [new UserController(), 'create']);
$router->post('/users', [new UserController(), 'store']);
$router->get('/users/:id', [new UserController(), 'show']);
$router->get('/users/:id/edit', [new UserController(), 'edit']);
$router->post('/users/:id', [new UserController(), 'update']);
$router->post('/users/:id/delete', [new UserController(), 'delete']);


