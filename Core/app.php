<?php
use Core\Router;
use Core\Config\AppConfig;
use Admin\Controllers\AuthController;
use Admin\Controllers\DashboardController;
use Admin\Controllers\AdminPostController;
use Admin\Controllers\AdminUserController;
use App\Controllers\PostController;

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

// Define routes using the Router class

// Home routes
Router::addRoute('/', $defaultController, 'GET', 'index');
Router::addRoute('/home', $defaultController, 'GET', 'index');

// Blog routes
Router::addRoute('/blog', PostController::class, 'GET', 'index');
Router::addRoute('/blog/{post_name}', PostController::class, 'GET', 'index');

// Dashboard routes
Router::addRoute('/dashboard', DashboardController::class, 'GET', 'index');

// Authentication routes
Router::addRoute('/login', AuthController::class,'GET', 'LoginForm');
Router::addRoute('/login', AuthController::class,'POST', 'login');
Router::addRoute('/register', AuthController::class,'GET', 'RegisterForm');
Router::addRoute('/register', AuthController::class,'POST', 'register');
Router::addRoute('/logout', AuthController::class,'GET', 'logout');

// Admin post routes
Router::addRoute('/posts', AdminPostController::class, 'GET', 'index');
Router::addRoute('/create-post', AdminPostController::class, 'GET', 'create');
Router::addRoute('/create-post', AdminPostController::class, 'POST', 'save');
Router::addRoute('/edit-post/{id}', AdminPostController::class, 'GET', 'edit');
Router::addRoute('/edit-post/{id}', AdminPostController::class, 'POST', 'update');
Router::addRoute('/delete-post/{id}', AdminPostController::class, 'POST', 'delete');

// Public post routes
Router::addRoute('/articles/{title}', PostController::class, 'GET', 'view');
Router::addRoute('/articles', PostController::class, 'GET', 'index');

// Admin user routes
Router::addRoute('/users', AdminUserController::class, 'GET', 'index');
Router::addRoute('/create-user', AdminUserController::class, 'GET', 'create');
Router::addRoute('/create-user', AdminUserController::class, 'POST', 'save');
Router::addRoute('/edit-user/{id}', AdminUserController::class, 'GET', 'edit');
Router::addRoute('/edit-user/{id}', AdminUserController::class, 'POST', 'update');
Router::addRoute('/delete-user/{id}', AdminUserController::class, 'POST', 'delete');
