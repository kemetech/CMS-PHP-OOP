<?php

/**
 * File: CsrfToken.php
 * Description: This file contains the CsrfToken class, which provides functionality for generating and validating CSRF tokens.
 */

namespace Core\Utils;

use Admin\Auth\Session;
use Exception;

class CsrfToken
{
    /**
     * Generate a CSRF token.
     *
     * @param int $length The length of the token in bytes.
     * @return string The generated CSRF token.
     */
    public static function generate($length = 32)
    {
        $token = bin2hex(random_bytes($length));
        Session::set('token', $token);
        return $token;
    }

    /**
     * Check if a CSRF token is valid.
     *
     * @param string $token The CSRF token to validate.
     * @return bool True if the token is valid, false otherwise.
     * @throws Exception if the CSRF token validation fails.
     */
    public static function check($token)
    {
        if (Session::has('token') && Session::get('token') === $token) {
            Session::delete('token');
            return true;
        }
        
        throw new Exception('CSRF token validation failed');
    }
}
