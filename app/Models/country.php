<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status'
    ];
    public function shippingCharge()
    {
        return $this->hasOne(ShippingCharge::class);
    }
    public function order()
    {
        return $this->hasOne(order::class);
    }
}

