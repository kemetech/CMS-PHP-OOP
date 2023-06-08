<?php

// Require necessary files
require_once '../Core/bootstrap.php';

use Admin\Controllers\DashboardController;
use Core\Router;
use Core\Config\AppConfig;
use Admin\Controllers\AuthController;
use Admin\Controllers\AdminPostController;
use App\Controllers\HomeController;
use Core\Config\UploadConfig;



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








// Add more admin routes as needed

// Handle the request
Router::route($_SERVER['REQUEST_METHOD'], $requestUri);
