<?php

namespace App\Model;

use App\Services\MongoModel;

class SessionMongo extends MongoModel
{
    protected $collection = 'sessions';
    protected $primaryKey = 'token';

    protected $fields = [
        'token',
        'user_id',
        'expired_at',
    ];

    protected $validations = [
        'expired_at' => [
            'required' => true,
            'type' => 'datetime',
        ],
    ];

    public function user()
    {
        return User::find($this->data['user_id']);
    }
}