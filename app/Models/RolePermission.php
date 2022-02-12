<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'roles_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'created_by',
    ];

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($item) {
            \Log::info('Role Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = \Auth::user()->id;
            \Log::info('Role Creating Event:'.$item);
        });

	}
}
