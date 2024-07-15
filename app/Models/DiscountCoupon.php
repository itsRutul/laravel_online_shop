<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class DiscountCoupon extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'product_name',
        'description',
        'max_uses',
        'max_users',
        'type',
        'discount_amount',
        'min_amount',
        'status',
        'starts_at',
        'expire_at',
        'uses', // Add the new fields here
        'users_used',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expire_at' => 'datetime',
    ];


    // Relationship with the products if necessary
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
