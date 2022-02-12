<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon',
        'discount_type',
        'discount_quantity',
        'mount_max_discount',
        'is_active',
        'created_by',
        'expired_at'
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

    public function discount_config()
    {
        return $this->hasMany(DiscountConfig::class, 'discount_id', 'id')->select('table_id','table_name','discount_id');
    }

    public function getFillable() {
        return $this->fillable;
    }

}
