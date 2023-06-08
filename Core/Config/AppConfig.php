<?php

/**
 * File: AppConfig.php
 * Description: This file contains the AppConfig class, which provides access to application configuration settings.
 */

namespace Core\Config;

use Core\Config\ConfigManager;

class AppConfig
{
    private static $version;
    private static $root;
    private static $defaultController;
    private static $defaultMethod;
    private static $home;
    private static $siteTitle;
    private static $loginCookieName;
    private static $defaultLayout;

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

        self::$version = $config['version'];
        self::$root = $config['root_dir'];
        self::$defaultController = $config['default_controller'];
        self::$home = $config['home_page'];
        self::$siteTitle = $config['site_title'];
        self::$loginCookieName = $config['login_cookie_name'];
        self::$defaultLayout = $config['default_layout'];
    }

    /**
     * Get the version of the application.
     *
     * @return string The application version.
     */
    public static function getVersion()
    {
        return self::$version;
    }

    /**
     * Get the root directory of the application.
     *
     * @return string The root directory.
     */
    public static function getRoot()
    {
        return self::$root;
    }

    /**
     * Get the default controller for the application.
     *
     * @return string The default controller.
     */
    public static function getDefaultController()
    {
        return self::$defaultController;
    }

    /**
     * Get the default method for the application.
     *
     * @return string The default method.
     */
    public static function getDefaultMethod()
    {
        return self::$defaultMethod;
    }

    /**
     * Get the default layout for the application.
     *
     * @return string The default layout.
     */
    public static function getDefaultLayout()
    {
        return self::$defaultLayout;
    }

    /**
     * Get the home page for the application.
     *
     * @return string The home page.
     */
    public static function getHomePage()
    {
        return self::$home;
    }

    /**
     * Get the site title for the application.
     *
     * @return string The site title.
     */
    public static function getSiteTitle()
    {
        return self::$siteTitle;
    }

    /**
     * Get the login cookie name for the application.
     *
     * @return string The login cookie name.
     */
    public static function getLoginCookieName()
    {
        return self::$loginCookieName;
    }

    // Add similar getter methods for other configuration details
    // ...
}
