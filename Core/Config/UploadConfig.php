<?php

/**
 * File: UploadConfig.php
 * Description: This file contains the UploadConfig class, which provides access to upload configuration settings.
 */

namespace Core\Config;

use Core\Config\ConfigManager;

class UploadConfig
{
    private static $maxSize;
    private static $allowedExtensions;
    private static $allowedMimeTypes;

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

        self::$maxSize = $config['max_size'];
        self::$allowedExtensions = $config['allowed_extensions'];
        self::$allowedMimeTypes = $config['allowed_mime_types'];
    }

    /**
     * Get the maximum allowed file size for uploads.
     *
     * @return int The maximum allowed file size in bytes.
     */
    public static function getMaxSize()
    {
        return self::$maxSize;
    }

    /**
     * Get the allowed file extensions for uploads.
     *
     * @return string A string of allowed file extensions.
     */
    public static function getAllowedExtensions()
    {
        return self::$allowedExtensions;
    }

    /**
     * Get the allowed file MIME types for uploads.
     *
     * @return string A string of allowed file MIME types.
     */
    public static function getAllowedMimeTypes()
    {
        return self::$allowedMimeTypes;
    }

    // Repeat similar getter methods for other configuration details
    // ...
}
