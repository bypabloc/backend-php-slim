<?php

namespace App\App;

class Settings
{
    public function __invoke(): void
    {
        putenv("DB_HOST=".$_SERVER['DB_HOST']."");
        putenv("DB_NAME=".$_SERVER['DB_NAME']."");
        putenv("DB_USER=".$_SERVER['DB_USER']."");
        putenv("DB_PASS=".$_SERVER['DB_PASS']."");
        putenv("DB_PORT=".$_SERVER['DB_PORT']."");
    }
}


// return [
//     'settings' => [
//         'displayErrorDetails' => filter_var($_SERVER['DISPLAY_ERROR_DETAILS'], FILTER_VALIDATE_BOOLEAN),
//         'db' => [
//             'host' => $_SERVER['DB_HOST'],
//             'name' => $_SERVER['DB_NAME'],
//             'user' => $_SERVER['DB_USER'],
//             'pass' => $_SERVER['DB_PASS'],
//             'port' => $_SERVER['DB_PORT'],
//         ],
//         'app' => [
//             'domain' => $_SERVER['APP_DOMAIN'],
//             'secret' => $_SERVER['SECRET_KEY'],
//         ],
//     ],
// ];