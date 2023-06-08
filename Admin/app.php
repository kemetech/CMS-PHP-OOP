<?php

// Define constants

// Require necessary files
require_once ROOT . 'vendor/autoload.php';
require_once CORE_DIR . 'bootstrap.php';
require_once CORE_DIR . 'app.php';

use Core\Router;
use Core\Config\AppConfig;


$baseDir = '';
$defaultController = AppConfig::getDefaultController();
// Check if running on the local server
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // Set the base directory for the local server
    $baseDir = AppConfig::getRoot();
} else {
    // Dynamically detect the base directory for the live server
    $baseDir = dirname($_SERVER['SCRIPT_NAME']);
}
$requestUri = substr($_SERVER['REQUEST_URI'], strlen($baseDir));

// Add admin routes
Router::addRoute('/admin', AdminController::class, 'GET', 'index');
// Add more admin routes as needed

// Handle the request
$requestMethod = $_SERVER['REQUEST_METHOD'];
echo '<pre>';
var_dump($_SERVER);
$requestUri = str_replace('/admin', '', $_SERVER['REQUEST_URI']);
Router::route($requestMethod, $requestUri);
