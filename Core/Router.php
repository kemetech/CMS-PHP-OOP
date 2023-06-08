<?php

/**
 * File: Router.php
 * Description: This file contains the Router class, responsible for routing requests to appropriate controllers and actions.
 */

namespace Core;

use Exception;

class Router
{
    /**
     * @var array $routes An array to store the defined routes.
     */
    private static $routes = [];

    /**
     * Adds a new route to the routes array.
     *
     * @param string $pattern The URL pattern to match.
     * @param string $controller The controller class name.
     * @param string $method The HTTP method associated with the route.
     * @param string $action The action method to call on the controller.
     * @param array $args (optional) Additional arguments for the controller action.
     * @return void
     */
    public static function addRoute($pattern, $controller, $method, $action, $args = [])
    {
        self::$routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'args' => $args
        ];
    }

    /**
     * Routes the request to the appropriate controller and action based on the method and URI.
     *
     * @param string $method The HTTP method of the request.
     * @param string $uri The URI of the request.
     * @return void
     * @throws Exception If the controller or action does not exist.
     */
    public static function route($method, $uri)
    {
        // Parse the URI and extract the path and query string
        $parsedUri = parse_url($uri);
        $path = $parsedUri['path'];
        $query = isset($parsedUri['query']) ? $parsedUri['query'] : '';

        // Loop through the defined routes and find a match
        foreach (self::$routes as $route) {
            // Check if the HTTP method matches
            if ($route['method'] != $method) {
                continue;
            }

            // Check if the path matches
            $pathRegex = preg_replace('/{([^\/]+)}/', '([^/]+)', $route['pattern']);
            preg_match('#^' . $pathRegex . '$#', $path, $matches);

            if (!$matches) {
                continue;
            }

            $params = array_slice($matches, 1);

            // Parse the query string into an array
            parse_str($query, $queryParams);

            // Instantiate the controller and call the action method with the parameters
            $controllerClass = $route['controller'];

            if (!class_exists($controllerClass)){
                throw new Exception('This controller ' . $controllerClass . ' does not exist. Please check the controller name and path.');
            }

            $action = $route['action'];
            $controller = new $controllerClass($route['args']);

            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], array_merge($params, [$queryParams]));
                return;
            } else {
                throw new Exception('This method ' . $action . ' does not exist inside the controller ' . $controllerClass . '. Please check the method name.');
            }
        }

        // If no route matches, show a 404 error
        Redirect::to(404);
    }
}
