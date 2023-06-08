<?php

/**
 * File: SessionConfig.php
 * Description: This file contains the SessionConfig class, which provides access to session configuration settings.
 */

namespace Core\Config;

use Core\Config\ConfigManager;

class SessionConfig
{
    private static $lifetime;
    private static $path;
    private static $secure;
    private static $httponly;
    private static $samesite;
    private static $strictmode;
    private static $readClose;

    /**
     * Constructor.
     *
     * @param string $configPath The path to the configuration file.
     * @param string $key        The key of the configuration section to retrieve.
     */
    public function __construct($configPath, $key)
    {
        $configManager = new ConfigManager($configPath, $key);
        $config = $configManager->getConfig();

        self::$lifetime = $config['lifetime'];
        self::$path = $config['path'];
        self::$secure = $config['secure'];
        self::$httponly = $config['httponly'];
        self::$samesite = $config['samesite'];
        self::$strictmode = $config['strict'];
        self::$readClose = $config['readclose'];
    }

    /**
     * Get the session lifetime.
     *
     * @return int The session lifetime in seconds.
     */
    public static function getLifetime()
    {
        return self::$lifetime;
    }

    /**
     * Get the session path.
     *
     * @return string The session path.
     */
    public static function getPath()
    {
        return self::$path;
    }

    /**
     * Check if session is secure.
     *
     * @return bool True if session is secure, false otherwise.
     */
    public static function getSecure()
    {
        return self::$secure;
    }

    /**
     * Check if session is HTTP only.
     *
     * @return bool True if session is HTTP only, false otherwise.
     */
    public static function getHttpOnly()
    {
        return self::$httponly;
    }

    /**
     * Get the session SameSite attribute.
     *
     * @return string The SameSite attribute value.
     */
    public static function getSameSite()
    {
        return self::$samesite;
    }

    /**
     * Check if session strict mode is enabled.
     *
     * @return bool True if strict mode is enabled, false otherwise.
     */
    public static function getStrictMode()
    {
        return self::$strictmode;
    }

    /**
     * Check if session read-close mode is enabled.
     *
     * @return bool True if read-close mode is enabled, false otherwise.
     */
    public static function getReadClose()
    {
        return self::$readClose;
    }

    // Repeat similar getter methods for other configuration details
    // ...
}
