<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Storage;

use Illuminate\Database\Capsule\Manager as Capsule;

class Image extends Model
{
    use Storage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_id',
        'table_name',
        'path',
    ];

    public function creatingImageProducts($image)
    {
        $this->path = self::saveProductImage($image);
    }

    public function creatingImageProductsReview($image)
    {
        $this->path = self::saveProductReviewImage($image);
    }

}