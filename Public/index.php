<?php

// Include the necessary files
require_once '../Core/bootstrap.php';
require_once '../Core/app.php';

use Core\Router;

// Use the Router class to handle the request
Router::route($_SERVER['REQUEST_METHOD'], $requestUri);
