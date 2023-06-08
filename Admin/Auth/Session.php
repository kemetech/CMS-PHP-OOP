<?php
namespace Admin\Auth;

use Core\Config\SessionConfig;

class Session  
{
    public static $sessionName = 'MY_APP_SESSION';
    private static $sessionRegenerateInterval = 300; // 5 minutes
    private static $sessionTimeout = 900; // 15 minutes
    private static $ipBlocklist = [];
    private static $accountLock;
    private static $config;
    private static $sessionLifetime;


    /**
     * Sets the cookie parameters for the session.
     *
     * @param SessionConfig $config The session configuration object.
     */
    public static function setCookieParams(SessionConfig $config)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(self::$sessionName);

            session_set_cookie_params([
                'lifetime' => $config->getLifetime(),
                'path' => $config->getPath(),
                'secure' => $config->getSecure(), // Only send the cookie over HTTPS
                'httponly' => $config->getHttpOnly(), // Prevent client-side access to the cookie
                'samesite' => $config->getSamesite(), // Enforce same-site policy
            ]);

            ini_set('session.use_strict_mode', $config->getStrictMode());
            session_start(['read_and_close' => $config->getReadClose()]);

            self::$sessionLifetime = $config->getLifetime();
        }
    }

    /**
     * Starts the session and performs necessary checks and actions.
     */
    public static function start()
    {
        // Start session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Regenerate session ID periodically
        if (self::shouldRegenerateId()) {
            self::regenerateId();
        }

        // Set session timeout
        if (self::shouldTimeout()) {
            self::destroySession();
        }

        // Check for account lockout
        if (self::$accountLock === true) {
            self::destroySession();
            die('This IP address is blocked. Please contact the administrator.');
        }

        self::regenerateId();
        self::set('creation_time', time());
        self::set('user_agent', $_SERVER['HTTP_USER_AGENT']);
        self::set('ip_address', $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Sets a value in the session.
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to set.
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a value from the session.
     *
     * @param string $key The key to get the value for.
     * @return mixed|null The value from the session if it exists, null otherwise.
     */
    public static function get($key)
    {
        if (self::has($key)) {
            // Retrieve the session data
            $value = $_SESSION[$key];
            return $value;
        }

        return null;
    }

    /**
     * Checks if a value exists in the session.
     *
     * @param string $key The key to check for.
     * @return bool Returns true if the key exists in the session, false otherwise.
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Deletes a value from the session.
     *
     * @param string $name The key to delete from the session.
     */
    public static function delete($name)
    {
        if (self::has($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Retrieves a value from the session and removes it.
     *
     * @param string $key The key to retrieve and remove from the session.
     * @param mixed $string The default value to return if the key doesn't exist in the session.
     * @return mixed The value from the session if it exists, or the default value otherwise.
     */
    public static function flash($key, $string = [])
    {
        if (self::has($key)) {
            $session = self::get($key);
            self::delete($key);
            return $session;
        } else {
            self::set($key, $string);
        }
    }

    /**
     * Regenerates the session ID.
     *
     * @param bool $status Whether to delete the old session data.
     */
    public static function regenerateId($status = true)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id($status);
            $_SESSION['regenerate_time'] = time();
        }
    }

    /**
     * Checks if the current session is valid.
     *
     * @return bool Returns true if the session is valid, false otherwise.
     */
    public static function isValidSession()
    {
        return self::has('creation_time') && (self::get('creation_time') + self::$sessionLifetime) >= time();
    }

    /**
     * Checks if the session ID needs to be regenerated based on the interval.
     *
     * @return bool Returns true if the session ID should be regenerated, false otherwise.
     */
    public static function shouldRegenerateId()
    {
        if (isset($_SESSION['regenerate_time']) && ($_SESSION['regenerate_time'] + self::$sessionRegenerateInterval) < time()) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the session has timed out based on the configured timeout duration.
     *
     * @return bool Returns true if the session has timed out, false otherwise.
     */
    public static function shouldTimeout()
    {
        if (isset($_SESSION['creation_time']) && ($_SESSION['creation_time'] + self::$sessionTimeout) < time()) {
            return true;
        }

        return false;
    }

    /**
     * Destroys the session and clears all session variables.
     */
    public static function destroySession()
    {
        // Destroy the session and unset all session variables
        session_unset();
        session_destroy();
    }
}
