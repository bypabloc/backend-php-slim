<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CartProduct extends Model
{
    use Pagination;

    protected $table = 'carts_products';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'price_old',
        'price',
        'qty',
        'observation',
        'state',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price_old' => 'float',
        'price' => 'float',
        'qty' => 'float',
        'state' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}