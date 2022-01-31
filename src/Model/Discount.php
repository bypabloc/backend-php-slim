<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Coupon;

class Discount extends Model
{
    use Pagination;

    protected $table = 'discounts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon',
        'discount_type',
        'discount_quantity',
        'mount_max_discount',
        'is_active',
        'created_by',
        'expired_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'discount_type' => 'integer',
        'mount_max_discount' => 'float',
        'is_active' => 'boolean',
        'created_by' => 'integer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function discount_config()
    {
        return $this->hasMany(DiscountConfig::class, 'discount_id', 'id')->select('table_id','table_name','discount_id');
    }

}