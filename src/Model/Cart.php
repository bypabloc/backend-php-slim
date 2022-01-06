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
        foreach ($products as $key => $product) {
            array_push($carts_products,[
                'cart_id' => $this->id,
                'user_id' => $this->user_id,
                'product_id' => $product['id'],
                'qty' => $product['qty'],
                'price_old' => $product['price'],
                'price' => $product['price'],
                'observation' => $product['observation'],
            ]);
        }
        CartProduct::insert($carts_products);
    }

    public function updateProducts($carts_products): void
    {
        foreach ($carts_products as $key => $cart_product_item) {
            $cart_product = CartProduct::find($cart_product_item['cart_product_id']);
            if($cart_product_item['qty']){
                $cart_product->qty = $cart_product_item['qty'];
            }
            if($cart_product_item['observation']){
                $cart_product->observation = $cart_product_item['observation'];
            }
            if(isset($cart_product_item['state'])){
                $cart_product->state = $cart_product_item['state'];
            }
            $cart_product->save();
        }
    }
    
    public function updateProductsPrices(): void
    {
        $cart = Cart::where('id',$this->id)->with('products')->first();
        if(!empty($cart->products)){
            $total = 0;
            $products = $cart->products;
            foreach ($products as $key => $product) {
                $cart_product = CartProduct::find($product['pivot']['id']);
                $cart_product->price = (float) $product['price'];
                $cart_product->save();
            }
        }
    }

    public function updateTotal(): void
    {
        $cart = Cart::where('id',$this->id)->with('products')->first();

        if(!empty($cart->products)){
            $total = 0;
            $products = $cart->products;
            foreach ($products as $key => $product) {
                $price = (float) $product['price'];
                $qty = (float) $product['pivot']['qty'];
                $state = (int) $product['pivot']['state'];
                if($state > 0){
                    $total += $price * $qty;
                }
            }
            $cart->price = $total;
            $cart->save();
        }
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
                'created_at',
                'updated_at',
            );
    }
}