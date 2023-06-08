<?php

/**
 * File: Input.php
 * Description: This file contains the Input class, responsible for handling user input data.
 */

namespace Core;

class Input
{
    /**
     * Checks if input data of a specified type exists.
     *
     * @param string $type (optional) The type of input data to check. Defaults to 'post'.
     * @return bool True if input data of the specified type exists, false otherwise.
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return !empty($_POST);
                break;
            case 'get':
                return !empty($_GET);
                break;
            case 'file':
                return !empty($_FILES);
                break;
            case 'cookie':
                return !empty($_COOKIE);
                break;
            case 'server':
                return !empty($_SERVER);
                break;
            case 'env':
                return !empty($_ENV);
                break;
            default:
                return false;
        }
    }

    /**
     * Retrieves input data of a specified type, optionally sanitized.
     *
     * @param string|null $inputName (optional) The name of the input to retrieve. Defaults to null.
     * @param string $sanitizeType (optional) The type of sanitization to apply. Defaults to 'string'.
     * @param string $type (optional) The type of input data to retrieve. Defaults to 'post'.
     * @param int $options (optional) Additional options for the input retrieval. Defaults to 0.
     * @return mixed|null The retrieved input data or null if not found.
     */
    public static function get($inputName = null, $sanitizeType = 'string', $type = 'post', $options = 0)
    {
        $input = null;
        $filter = FILTER_DEFAULT;

        switch ($sanitizeType) {
            case 'string':
                $filter = FILTER_SANITIZE_SPECIAL_CHARS;
                break;
            case 'email':
                $filter = FILTER_SANITIZE_EMAIL;
                break;
            case 'number':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;
        }

        switch ($type) {
            case 'post':
                $input = filter_input(INPUT_POST, $inputName, $filter, $options);
                break;
            case 'get':
                $input = filter_input(INPUT_GET, $inputName, $filter, $options);
                break;
            case 'file':
                $input = $_FILES[$inputName] ?? null;
                break;
            case 'cookie':
                $input = filter_input(INPUT_COOKIE, $inputName, $filter, $options);
                break;
            case 'server':
                $input = filter_input(INPUT_SERVER, $inputName, $filter, $options);
                break;
            case 'env':
                $input = filter_input(INPUT_ENV, $inputName, $filter, $options);
                break;
        }

        return $input !== false ? $input : null;
    }
}
