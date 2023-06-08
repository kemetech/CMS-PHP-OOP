<?php

/**
 * File: EncryptionUtil.php
 * Description: This file contains the EncryptionUtil class, which provides functionality for encrypting and decrypting data.
 */

namespace Core\Utils;

class EncryptionUtil
{
    private static $key;

    /**
     * Generate the encryption key.
     */
    protected static function generateKey()
    {
        if (self::$key === null) {
            self::$key = random_bytes(32);
        }
    }

    /**
     * Get the encryption key.
     *
     * @return string The encryption key.
     */
    public static function getKey()
    {
        self::generateKey();
        return self::$key;
    }

    /**
     * Encrypt data using AES-256-CBC encryption.
     *
     * @param string $data The data to encrypt.
     * @return string The encrypted data.
     */
    public static function encrypt($data)
    {
        $key = self::getKey();
        $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        return base64_encode($iv . $encryptedData);
    }

    /**
     * Decrypt data using AES-256-CBC decryption.
     *
     * @param string $encryptedData The encrypted data to decrypt.
     * @return string The decrypted data.
     */
    public static function decrypt($encryptedData)
    {
        $key = self::getKey();
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, openssl_cipher_iv_length('AES-256-CBC'));
        $encryptedData = substr($data, openssl_cipher_iv_length('AES-256-CBC'));

        return openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, $iv);
    }

    // ... Additional methods can be added here for further encryption-related functionality
}
