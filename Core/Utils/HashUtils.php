<?php

/**
 * File: HashUtils.php
 * Description: This file contains the HashUtils class, which provides functionality for hashing and verifying passwords.
 */

namespace Core\Utils;

class HashUtils
{
    /**
     * Hash a password using bcrypt.
     *
     * @param string $password The password to hash.
     * @return string The hashed password.
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify a password against a hashed password.
     *
     * @param string $password The password to verify.
     * @param string $hashedPassword The hashed password to compare against.
     * @return bool True if the password is verified, false otherwise.
     */
    public static function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
