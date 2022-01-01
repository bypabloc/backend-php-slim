<?php

namespace App\Services;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JWT
{

    private static $key;

    public function __construct(){
        $this->key = getenv("SECRET_KEY");
    }

    public static function TimeExpired() : object
    {
        $expirationInSeconds = 60 * 60; // one hour
        $dateTokenExpiration = time() + $expirationInSeconds * 1000;

        return (object) [
            'dateTokenExpiration' => $dateTokenExpiration,
            'expirationInSeconds' => $expirationInSeconds
        ];
        // return time() + (60 * 60 * 24 * 7);
    }

    public static function GenerateToken(
        string $uuid,
        int $user_id,
    ) : string
    {
        // print_r(self::TimeExpired());die;
        $payload = [
            'uuid' => $uuid,
            'exp' => self::TimeExpired()->dateTokenExpiration
        ];

        return FirebaseJWT::encode($payload, self::$key, 'HS256');
    }

}