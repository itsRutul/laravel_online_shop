<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCouponController extends Controller
{
    public function index(Request $request)
    {   
        $query=DiscountCoupon::query();
        if ($request->has('table_search')) {
            $query->where('name', 'LIKE', "%{$request->table_search}%");
        }

        $discountCoupons = $query->latest()->paginate(10);
        return view('admin.discount-coupons.index', compact('discountCoupons'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.discount-coupons.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:discount_coupons,code',
            'product_name' => 'nullable|string',
            'description' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'status' => 'required|integer|in:0,1',
            'starts_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DiscountCoupon::create($request->all());

        return redirect()->route('discount-coupons.index')->with('success', 'Discount coupon created successfully.');
    }

    public function edit($id)
    {
        $discountCoupon = DiscountCoupon::findOrFail($id);
        $products = Product::all();
        return view('admin.discount-coupons.edit', compact('discountCoupon', 'products'));
    }

    public function update(Request $request, $id)
    {
        $discountCoupon = DiscountCoupon::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:discount_coupons,code,' . $discountCoupon->id,
            'product_name' => 'nullable|string',
            'description' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'status' => 'required|integer|in:0,1',
            'starts_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $discountCoupon->update($request->all());

        return redirect()->route('discount-coupons.index')->with('success', 'Discount coupon updated successfully.');
    }

    public function destroy($id)
    {
        $discountCoupon = DiscountCoupon::withTrashed()->findOrFail($id);
        $discountCoupon->forceDelete();
    
        return redirect()->route('discount-coupons.index')->with('success', 'Discount coupon permanently deleted successfully.');
    }
    

    
}
