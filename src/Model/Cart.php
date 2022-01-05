<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Cart extends Model
{
    use Pagination;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'price',
        'observation',
        'address',
        'state',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'state' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function creatingCustom()
    {
    }

    public function addProducts($products): void
    {
        $carts_products = [];
        $products_to_update = [];
        $total = 0;
        foreach ($products as $key => $product) {
            array_push($carts_products,[
                'cart_id' => $this->id,
                'product_id' => $product['id'],
                'qty' => $product['qty'],
                'price' => $product['price'],
                'observation' => $product['observation'],
            ]);
            $products_to_update[$product['id']] = [
                'stock' => (float) $product['stock'] - $product['qty'],
            ];
            $total += (int) $product['qty'] * (float) $product['price'];
        }
        CartProduct::insert($carts_products);
        Product::updateValues($products_to_update);
        
        Cart::where('id',$this->id)->update(['price' => $total]);
    }

    public function products()
    {
        return 
            $this->belongsToMany(Product::class, 'carts_products', 'cart_id', 'product_id')
            ->withPivot(
                'id',
                'price',
                'qty',
                'observation',
                'state',
            );
    }
}