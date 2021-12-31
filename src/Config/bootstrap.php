<?php

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
   "driver" => "pgsql",
   "host" => getenv("DB_HOST"),
   "database" => getenv("DB_NAME"),
   "username" => getenv("DB_USER"),
   "password" => getenv("DB_PASS"),
   "port" => getenv("DB_PORT"),
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();