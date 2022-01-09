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
        'user_id',
    ];

    public const STATES = [
        0 => 'Canceled', // seller y buyer -> only has been sent (step 3)
        1 => 'Request', // buyer
        2 => 'Paid', // seller
        3 => 'Sent', // seller
        4 => 'Delivered', // buyer
        5 => 'Finalized', // seller
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'price_old' => 'float',
        'price' => 'float',
        'qty' => 'float',
        'state' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'product_id');
    }

    public function productDiscountStock(): void
    {
        $this->product->discountStock($this->qty);
    }
}