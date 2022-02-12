<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public static $env;
    
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'is_active',
        'created_by'
    ];

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($item) {
            \Log::info('Role Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' || config('app.env') === 'local' ? 1 : \Auth::user()->id;
            \Log::info('Role Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

    public function permissions()
    {
        // https://laravel.com/docs/8.x/eloquent-relationships#many-to-many
        return $this->belongsToMany(Permission::class, 'roles_permissions', 'role_id', 'permission_id');
    }

    public function can($permission)
    {
        return !empty($this->permissions->where('alias',$permission)->toArray()) ? true : false;
    }
}
