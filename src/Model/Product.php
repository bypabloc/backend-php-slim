<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Capsule\Manager as DB;

use App\Services\Slug;
use App\Services\Storage;

use Illuminate\Database\Capsule\Manager as Capsule;

class Product extends Model
{
    use Pagination;
    use Storage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',

        'price',

        'discount_type',
        // 1 = percentage
        // 2 = amount
        'discount_quantity',

        'stock',

        'weight',
        'height',
        'width',
        'length',

        'state',

        'user_id',

        'product_category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'discount_type' => 'integer',
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
        $this->slug = rand(1, 999999999) . "-" . Slug::make($this->name);
        if(!empty($this->image)){
            $this->image = self::saveProductImage($this->image);
        }
    }

    public function updatingCustom()
    {
        if ($this->name != $this->getOriginal('name')) {
            $this->slug = explode("-", $this->getOriginal('slug'))[0] . "-" . Slug::make($this->name);
        }
        if(isset($this->image)){
            $this->image = self::saveProductImage($this->image);
        }
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'table_id', 'id')->select('id','path','table_id')->where('table_name', 'products');
    }

    public function rating()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id')
            ->select([
                DB::raw('ROUND(AVG(rating),2) as rating'),
                'product_id',
            ])
            ->groupBy('product_id');
    }

    public function discountStock(
        int $qty,
    )
    {
        $this->stock -= $qty;
        $this->save();
    }

    public function restoreStock(
        int $qty,
    )
    {
        $this->stock += $qty;
        $this->save();
    }
    
    public static function updateValues(array $values)
    {
        foreach ($values as $key => $value) {
            Product::find($key)->update($value);
        }
    }

    public function related()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'product_category_id')->where('id', '!=', $this->id);
    }

    public function salesCanceled()
    {
        return 
            $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
                ->withPivot(
                    'id',
                    'price_old',
                    'price',
                    'qty',
                    'observation',
                    'state',
                    'created_at',
                    'updated_at',
                )
                ->as('product')
                ->where('carts_products.state', 0)
                ->where('carts.state', 2);
    }

    public function salesRequest()
    {
        return 
            $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
                ->withPivot(
                    'id',
                    'price_old',
                    'price',
                    'qty',
                    'observation',
                    'state',
                    'created_at',
                    'updated_at',
                )
                ->as('product')
                ->where('carts_products.state', 1)
                ->where('carts.state', 2);
    }

    public function salesPaid()
    {
        return 
            $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
                ->withPivot(
                    'id',
                    'price_old',
                    'price',
                    'qty',
                    'observation',
                    'state',
                    'created_at',
                    'updated_at',
                )
                ->as('product')
                ->where('carts_products.state', 2)
                ->where('carts.state', 2);
    }

    public function salesSent()
    {
        return $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
            ->withPivot(
                'id',
                'price',
                'qty',
                'observation',
                'state',
                'created_at',
                'updated_at',
            )
            ->as('product')
            ->where('carts_products.state', 3)
            ->where('carts.state', 2);
    }

    public function salesDelivered()
    {
        return 
            $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
                ->withPivot(
                    'id',
                    'price_old',
                    'price',
                    'qty',
                    'observation',
                    'state',
                    'created_at',
                    'updated_at',
                )
                ->as('product')
                ->where('carts_products.state', 4)
                ->where('carts.state', 2);
    }

    public function salesFinalized()
    {
        return 
            $this->belongsToMany(Cart::class, 'carts_products', 'product_id', 'cart_id')
                ->withPivot(
                    'id',
                    'price_old',
                    'price',
                    'qty',
                    'observation',
                    'state',
                    'created_at',
                    'updated_at',
                )
                ->as('product')
                ->where('carts_products.state', 5)
                ->where('carts.state', 2);
    }
}