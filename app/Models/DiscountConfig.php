<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountConfig extends Model
{
    use HasFactory;

    protected $table = 'discounts_configs';

    protected $fillable = [
        'table_id',
        'table_name',
        'discount_id'
    ];

}
