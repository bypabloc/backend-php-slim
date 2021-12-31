<?php

namespace App\Services;

class Hash
{
    public static function make($password, $cost=11){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function validate($password, $hash){
        return password_verify($password, $hash);
    }
}