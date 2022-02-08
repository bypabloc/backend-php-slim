<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($item) {
            \Log::info('Permission Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = \Auth::user()->id;
            \Log::info('Permission Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

}
