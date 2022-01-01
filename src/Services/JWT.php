<?php

namespace App\Services;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

use App\Model\Session;

class JWT
{

    private static $key;

    public function __construct(){
        $this->key = getenv("SECRET_KEY");
    }

    public static function TimeExpired() : object
    {
        $expirationInSeconds = 60 * 60; // one hour
        $dateTokenExpiration = time() + $expirationInSeconds;
        $dateTokenFormat = date('Y-m-d H:i:s', $dateTokenExpiration);

        return (object) [
            'dateTokenFormat' => $dateTokenFormat,
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
        $dateTokenExpiration = self::TimeExpired()->dateTokenExpiration;
        $dateTokenFormat = self::TimeExpired()->dateTokenFormat;

        $payload = [
            'uuid' => $uuid,
            'exp' => $dateTokenExpiration
        ];

        $token = FirebaseJWT::encode($payload, self::$key, 'HS256');

        self::DestroyTokens($user_id);

        Session::create([
            'token' => $token,
            'user_id' => $user_id,
            'expired_at' => $dateTokenFormat,
        ]);

        return $token;
    }

    public static function DestroyTokens(
        int $user_id,
    ) : void
    {
        Session::where('user_id', $user_id)->delete();
    }

}