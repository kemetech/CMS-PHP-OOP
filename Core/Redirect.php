<?php

/**
 * File: Redirect.php
 * Description: This file contains the Redirect class, responsible for handling HTTP redirects.
 */

namespace Core;

class Redirect
{
    /**
     * Redirects the user to a specified location.
     *
     * @param string|null $location (optional) The URL or path to redirect to. Defaults to null.
     * @return void
     */
    public static function to($location = null)
    {
        $location = url($location);

        if ($location) {
            ob_start();

            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include './App/includes/404.html';
                        ob_end_flush();
                        exit();
                        break;
                }
            }

            header('Location: ' . $location);
            ob_end_flush();
            exit;
        }
    }

    /**
     * Refreshes the current page or redirects to a specified location after a given time.
     *
     * @param string|null $location (optional) The URL or path to redirect to. Defaults to null.
     * @param int $time (optional) The time in seconds before the redirect occurs. Defaults to 5.
     * @return void
     */
    public static function refresh($location = null, $time = 5)
    {
        header("refresh: $time; url=$location");
    }
}