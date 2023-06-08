<?php

/**
 * File: MysqlConfig.php
 * Description: This file contains the MysqlConfig class, which provides access to MySQL database configuration settings.
 */

namespace Core\Config;

use Core\Config\ConfigManager;

class MysqlConfig
{
    private static $host;
    private static $port;
    private static $username;
    private static $password;
    private static $dbName;
    private static $dsn;

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

        self::$host = $config['host'];
        self::$username = $config['username'];
        self::$password = $config['password'];
        self::$dbName = $config['dbname'];
        self::$dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbName;
    }

    /**
     * Get the MySQL host.
     *
     * @return string The host.
     */
    public static function getHost()
    {
        return self::$host;
    }

    /**
     * Get the MySQL port.
     *
     * @return int The port.
     */
    public static function getPort()
    {
        return self::$port;
    }

    /**
     * Get the MySQL username.
     *
     * @return string The username.
     */
    public static function getUser()
    {
        return self::$username;
    }

    /**
     * Get the MySQL password.
     *
     * @return string The password.
     */
    public static function getPass()
    {
        return self::$password;
    }

    /**
     * Get the MySQL database name.
     *
     * @return string The database name.
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     * Get the MySQL DSN (Data Source Name).
     *
     * @return string The DSN.
     */
    public static function getDsn()
    {
        return self::$dsn;
    }

    // Repeat similar getter methods for other configuration details
    // ...
}