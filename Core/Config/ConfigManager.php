<?php

/**
 * File: ConfigManager.php
 * Description: This file contains the ConfigManager class, responsible for managing configuration data.
 */

namespace Core\Config;

class ConfigManager
{
    private $configData;

    /**
     * Constructor.
     *
     * @param string $configFilePath The path to the configuration file.
     * @param string $key            The key of the configuration section to retrieve.
     */
    public function __construct($configFilePath, $key)
    {
        // Read and parse the configuration file
        $this->configData = parse_ini_file($configFilePath, true)[$key];
    }

    /**
     * Get the configuration data.
     *
     * @return array The configuration data.
     */
    public function getConfig()
    {
        // Get the value for a specific configuration key
        return $this->configData;
    }
}