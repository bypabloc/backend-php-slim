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
        'product_id',
        'category_id',
        'user_id',
        'discount_type',
        'discount_quantity',
        'mount_mx_dsc',
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
        'category_id' => 'integer',
        'product_id' => 'integer',
        'user_id' => 'integer',
        'discount_type' => 'integer',
        'mount_mx_dsc' => 'float',
        'is_active' => 'boolean',
        'created_by' => 'integer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function creatingCustom()
    {
        $this->coupon = Slug::generateRandomCodes($this->coupon);
    }

}