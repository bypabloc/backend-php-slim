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

    const STATES = [
        0 => 'anulado',
        1 => 'solicitado',
        2 => 'pagado',
        3 => 'enviado',
        4 => 'recibido',
        5 => 'finalizado',
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
}