<?php
namespace Core;

class Sanitize
{

 
    public static function input($input){
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public static function email($email){
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

}