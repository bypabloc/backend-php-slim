<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Slug;

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
    }

    public function updatingCustom()
    {
        $this->slug = Slug::make($this->name);
    }
}