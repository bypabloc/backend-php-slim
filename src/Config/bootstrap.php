<?php

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$config = [
   "driver" => "pgsql",
   "host" => getenv("DB_HOST"),
   "database" => getenv("DB_NAME"),
   "username" => getenv("DB_USER"),
   "password" => getenv("DB_PASS"),
   "port" => getenv("DB_PORT"),
];

// print_r($config);

$capsule->addConnection($config);

$capsule->setAsGlobal();
$capsule->bootEloquent();