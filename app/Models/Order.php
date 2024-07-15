<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'user_id','subtotal','shipping','coupon_code','discount','grand_total','first_name','last_name','email','mobile','country_id','address','apartment','city','state','zip','notes','status'];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship to the Country model
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function shippingAddress()
    {
        return $this->hasOne(CustomerAddress::class);
    }
    public function discountCoupon()
    {
    return $this->belongsTo(DiscountCoupon::class, 'coupon_code', 'code');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
