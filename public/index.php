<?php

require __DIR__ . '/../config/autoload.php';
session_start();

use Core\Router;

$router = new Router();

// Add routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('language/switch', ['controller' => 'Language', 'action' => 'switch']);
$router->add('posts/{id}', ['controller' => 'Posts', 'action' => 'view']);

// Dispatch the request
$url = $_SERVER['REQUEST_URI'] ?? '';

$router->dispatch($url);
