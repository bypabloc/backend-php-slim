<?php

namespace App\Services;

class Coupon
{
    public static function generateRandomCodes($number) {
        $codes = Collection::times($number, function () { Str::random(8); });        
    }
}