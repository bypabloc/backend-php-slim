<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Hash;
use Ramsey\Uuid\Uuid;

use App\Services\JWT;

class User extends Model
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
        'uuid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function creatingCustom()
    {
        $this->password = Hash::make($this->password);
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function createdCustom()
    {
        $this->token = JWT::GenerateToken($this->uuid, $this->id);
    }

    public function updatingCustom()
    {
        if(!empty($this->password)){
            $this->password = Hash::make($this->password);
        }
    }

    public function generateToken()
    {
        $this->token = JWT::GenerateToken($this->uuid, $this->id);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}