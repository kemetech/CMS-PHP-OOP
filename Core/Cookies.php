<?php
namespace Core;

class Cookies
{
    public static function set($key, $value, $expiry)
    {
        if (setcookie($key, $value, time()+$expiry, '/')){
            return true;
        } 
    }

    public static function exists($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function get($key)
    {
        if (self::exists($key)){
            return $_COOKIE[$key];
        }
    }
    public static function delete($key)
    {
        return self::set($key, '', -1);
    }
}