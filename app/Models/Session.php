<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Session extends Model
{
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('app.env') === 'testing' ? 'mongodb_test' : 'mongodb';
    }

    protected $collection = 'sessions';

    protected $fillable = [
        'token',
        'user_id',
        'expired_at',
    ];
}
