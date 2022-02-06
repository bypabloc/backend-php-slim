<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Services\Slug;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'products_categories';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'user_id',
        'parent_id',
        'created_by',
    ];

        /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($item) {
            \Log::info('Item Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = 1;
            \Log::info('Item Creating Event:'.$item);
        });

	}

    public function creatingCustom()
    {

        $this->slug = Slug::make($this->name);
        print_r($this->slug);
    }

    public function updatingCustom()
    {
        $this->slug = Slug::make($this->name);
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    }

    public function isActive()
    {
        return $this->where('is_active', true);
    }

    public function getFillable() {
        return $this->fillable;
    }

}
