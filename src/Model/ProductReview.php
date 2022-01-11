<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProductReview extends Model
{
    use Pagination;

    protected $table = 'products_reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'parent_id',
        'content',
        'rating',
        'user_id',
    ];

    public function children()
    {
        return $this->hasMany(ProductReview::class, 'parent_id', 'id');
    }
}