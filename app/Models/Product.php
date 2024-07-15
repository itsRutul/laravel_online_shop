<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'brand_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
        'size',
        'color',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
{
    return $this->belongsTo(SubCategory::class, 'sub_category_id');
}

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}

