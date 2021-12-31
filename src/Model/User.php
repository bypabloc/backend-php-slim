<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    use Pagination;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'nickname',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

}