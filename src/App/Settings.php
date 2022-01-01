<?php

namespace App\App;

class Settings
{
    public function __invoke(): void
    {
        $baseDir = __DIR__ . '/../../';
        $envPath = $baseDir . '.env';
        if (file_exists($envPath)) {
            $envFile = file_get_contents($envPath, FILE_USE_INCLUDE_PATH);
            $array = preg_split("/\r\n|\n|\r/", $envFile);
            foreach ($array as $line) {
                $line = trim($line);
                if (empty($line) || $line[0] == '#') {
                    continue;
                }
                $line = explode('=', $line, 2);
                if (count($line) == 2) {
                    putenv(trim($line[0]) . '=' . trim($line[1]));
                    // putenv("DB_HOST=".$_SERVER['DB_HOST']."");
                }
            }
        }
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