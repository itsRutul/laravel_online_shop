<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Correct use statement for Log
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\DiscountCoupon;
use App\Models\Order;


class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();

        $discount = Session::get('discount', 0); // Get discount from session
        $totalAfterDiscount = $total - $discount;

        return view('front.cart', compact('cart', 'total', 'discount', 'totalAfterDiscount', 'categories', 'subcategories'));
    }


    public function applyCoupon(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated.']);
            }

            $couponCode = $request->input('coupon_code');
            $productId = $request->input('product_id');
            $cart = Session::get('cart', []);
            $subtotal = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            $coupon = DiscountCoupon::where('code', $couponCode)
                ->where('starts_at', '<=', now())
                ->where('expire_at', '>=', now())
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                return response()->json(['success' => false, 'message' => 'Invalid coupon code or coupon is not active.']);
            }

            // Check if the coupon is already used in session
            $usedCoupons = Session::get('used_coupons', []);

            if (in_array($couponCode, $usedCoupons)) {
                return response()->json(['success' => false, 'message' => 'This coupon has already been used.']);
            }

            if ($coupon->max_uses !== null && $coupon->uses >= $coupon->max_uses) {
                return response()->json(['success' => false, 'message' => 'Coupon has reached its maximum usage limit.']);
            }

            if ($coupon->min_amount !== null && $subtotal < $coupon->min_amount) {
                return response()->json(['success' => false, 'message' => 'Minimum purchase amount not reached to use this coupon.']);
            }

            $userUsedCoupon = Order::where('user_id', $user->id)
                ->where('coupon_code', $couponCode)
                ->exists();

            if ($userUsedCoupon) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
            }

            $productIdValid = false;
            if ($coupon->product_name) {
                foreach ($cart as $item) {
                    if ($item['id'] == $coupon->product_name) { // Validate the coupon for the specific product
                        $productIdValid = true;
                        break;
                    }
                }
                if (!$productIdValid) {
                    return response()->json(['success' => false, 'message' => 'Coupon is not valid for the selected product.']);
                }
            }

            // Calculate discount based on coupon type
            $discount = 0;
            if ($coupon->type === 'fixed') {
                $discount = $coupon->discount_amount; // Fixed amount discount
            } elseif ($coupon->type === 'percentage') {
                $discount = $subtotal * ($coupon->discount_amount / 100); // Percentage discount
            }

            // Store the coupon details in session
            Session::put('discount', $discount);
            Session::put('coupon_code', $couponCode);

            return response()->json([
                'success' => true,
                'discount' => $discount,
                'final_total' => $subtotal - $discount
            ]);

        } catch (\Exception $e) {
            Log::error('Error applying coupon: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while applying the coupon.', 'error' => $e->getMessage()]);
        }
    }







public function addToCart(Request $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }

    $cart = session()->get('cart', []);
    $size = $request->input('size');
    $color = $request->input('color');
    $price = $product->price;

    // Create a unique key for the cart item based on product ID, size, color, and price
    $cartItemKey = $id . '_' . $size . '_' . $color . '_' . $price;

    // Find the image that matches the selected color
    $images = json_decode($product->image);
    $selectedImage = '';
    foreach ($images as $image) {
        if (strpos($image, $color) !== false) {
            $selectedImage = $image;
            break;
        }
    }

    // Check if the exact item (same ID, size, color, and price) is already in the cart
    if (isset($cart[$cartItemKey])) {
        return response()->json(['success' => false, 'message' => 'Product is already in the cart with the same attributes.']);
    }

    // Add the item to the cart
    $cart[$cartItemKey] = [
        "id" => $product->id,
        "name" => $product->title,
        "quantity" => 1,
        "price" => $price,
        "size" => $size,
        "color" => $color,
        "image" => $selectedImage,
    ];

    session()->put('cart', $cart);

    return response()->json(['success' => true, 'message' => 'Product added to cart.', 'cart' => $cart]);
}

    // Other methods in your CartController



    public function updateCart(Request $request)
{
    $cartItemKey = $request->id; // The ID here will be the unique key
    if ($cartItemKey && $request->quantity) {
        $cart = Session::get('cart', []);
        if (isset($cart[$cartItemKey])) {
            $cart[$cartItemKey]['quantity'] = $request->quantity;
            Session::put('cart', $cart);
        }
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
}

public function removeFromCart(Request $request)
{
    $cartItemKey = $request->id; // The ID here will be the unique key
    if ($cartItemKey) {
        $cart = Session::get('cart');
        if (isset($cart[$cartItemKey])) {
            unset($cart[$cartItemKey]);
            Session::put('cart', $cart);
        }

        // If the cart is empty, remove the discount and coupon code from the session
        if (empty($cart)) {
            Session::forget('discount');
            Session::forget('coupon_code');
        }

        // Debugging output


        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
}


}
