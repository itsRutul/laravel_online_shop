<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Order;
use App\Models\Wishlist;

class AccountController extends Controller
{
    public function myorders()
    {
        // Fetch orders for the logged-in user
        $orders = Order::where('user_id', Auth::id())->get();
        $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();

        return view('front.account.my-orders', compact('orders', 'categories', 'subcategories'));
    }

    public function orderdetails($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();

        return view('front.account.order-details', compact('order', 'categories', 'subcategories'));
    }

    public function wishlist()
    {
        $wishlistProducts = Auth::user()->wishlist()->with('product')->get();
        $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();

        return view('front.account.wishlist', compact('wishlistProducts', 'categories', 'subcategories'));
    }

    public function addToWishlist(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');

        // Check if the product is already in the wishlist
        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            return redirect()->back()->with('error', 'Product already in wishlist.');
        }

        // Add product to wishlist
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->product_id = $productId;
        $wishlist->save();

        return redirect()->back()->with('success', 'Product added to wishlist.');
    }

    public function removeFromWishlist(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');

        // Find the wishlist item and delete it
        $wishlistItem = $user->wishlist()->where('product_id', $productId)->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return redirect()->back()->with('success', 'Product removed from wishlist.');
        }

        return redirect()->back()->with('error', 'Product not found in wishlist.');
    }

    public function showChangePasswordForm()
    {
        $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
        return view('front.account.change-password', compact('categories', 'subcategories'));
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->back()->with('error', 'Incorrect old password.');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
