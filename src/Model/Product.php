<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Slug;
use App\Services\Storage;

class Product extends Model
{
    use Pagination;

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

        'image',

        'weight',
        'height',
        'width',
        'length',

        'likes',

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
        $this->slug = Slug::make($this->name);

        if(isset($this->image)){
            $name_file = time() . bin2hex(random_bytes(50));
            $this->image = $this->save_base64_image($this->image, $name_file ,'product_images');
        }
    }

    public function updatingCustom()
    {
        $this->slug = Slug::make($this->name);
        if(isset($this->image)){
            $name_file = time() . bin2hex(random_bytes(50));
            $this->image = $this->save_base64_image($this->image, $name_file ,'product_images');
        }
    }
}