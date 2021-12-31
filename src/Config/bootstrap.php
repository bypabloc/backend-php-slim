<?php

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
   "driver" => "pgsql",
   "host" =>"127.0.0.1",
   "database" => "php_slim_test",
   "username" => "postgres",
   "password" => "123456",
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();