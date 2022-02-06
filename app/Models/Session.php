<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Session extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sessions';
}
