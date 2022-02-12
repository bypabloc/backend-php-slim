<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('app.env') === 'testing' ? 'mongodb_test' : 'mongodb';
    }

    protected $collection = 'sessions';

    protected $primaryKey = 'token';

    protected $fillable = [
        'token',
        'user_id',
        'ip_address',
        'user_agent',
        'expired_at',
    ];

    public function isDeleted()
    {
        return $this->deleted_at !== null;
    }

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::creating(function($item) {
            $item->ip_address = session('ipAddress', '');
            $item->user_agent = session('userAgent', '');
            \Log::info('Session Creating Event:'.$item);
        });

        static::created(function($item) {
            \Log::info('Session Created Event:'.$item);
        });
        
	}
}
