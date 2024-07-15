<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ShippingCharge;
use App\Models\DiscountCoupon; // Ensure DiscountCoupon model is imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $customerAddress = auth()->user()->customerAddress ?? null;

        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Get discount and coupon code from session
        $discount = session()->get('discount', 0);
        $couponCode = session()->get('coupon_code', null);
        $totalAfterDiscount = $subtotal - $discount;

        // Calculate shipping cost if a shipping address is present
        $shipping = 0;
        if ($customerAddress) {
            $selectedCountry = Country::where('id', $customerAddress->country_id)->first();
            if ($selectedCountry && $selectedCountry->shippingCharge) {
                $shipping = $selectedCountry->shippingCharge->amount;
            }
            $totalAfterDiscount += $shipping; // Add shipping cost to total
        }

        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
        $countries = Country::with('shippingCharge')->get();

        return view('front.checkout', compact('cart', 'subtotal', 'totalAfterDiscount', 'discount', 'couponCode', 'countries', 'categories', 'subcategories', 'shipping', 'customerAddress'));
    }

    public function process(Request $request)
{
    \Log::info('Checkout process started'); // Add this line for logging

    $cart = session()->get('cart', []);

    // Check if cart is empty
    if (empty($cart)) {
        return response()->json([
            'message' => 'No items in the cart to checkout.',
        ], 400);
    }

    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'mobile' => 'required|string|max:15',
        'country' => 'required|exists:countries,id',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'zip' => 'required|string',
        'payment_method' => 'required|string|in:cod,card',
    ]);

    // Additional logs for debugging
    \Log::info('Validation passed', $request->all());

    $user = Auth::user();
    $subtotal = array_reduce($cart, function ($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);
    $discount = session()->get('discount', 0);
    $couponCode = session()->get('coupon_code', null);

    $country = Country::findOrFail($request->input('country'));
    $shippingCharge = ShippingCharge::where('country_id', $country->id)->first();
    $shipping = $shippingCharge ? $shippingCharge->amount : 0;

    $total = $subtotal + $shipping - $discount;

    // Update coupon usage statistics
    if ($couponCode) {
        $coupon = DiscountCoupon::where('code', $couponCode)
            ->where('starts_at', '<=', now())
            ->where('expire_at', '>=', now())
            ->where('status', 1)
            ->first();

        if ($coupon) {
            $coupon->increment('uses');
            $coupon->increment('users_used');
            $coupon->save();
        }
    }

    $order = Order::create([
        'user_id' => $user->id,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'coupon_code' => $couponCode,
        'discount' => $discount,
        'grand_total' => $total,
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'email' => $request->input('email'),
        'mobile' => $request->input('mobile'),
        'country_id' => $country->id,
        'address' => $request->input('address'),
        'apartment' => $request->input('apartment', null),
        'city' => $request->input('city'),
        'state' => $request->input('state'),
        'zip' => $request->input('zip'),
        'notes' => $request->input('order_notes', null),
    ]);

    foreach ($cart as $cartItem) {
        // Assuming $cartItem['id'] represents the product ID
        $product = Product::find($cartItem['id']);

        if ($product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id, // Use the actual product ID here
                'name' => $product->title, // Ensure 'title' matches your Product model attribute
                'qty' => $cartItem['quantity'],
                'price' => $cartItem['price'],
                'total' => $cartItem['price'] * $cartItem['quantity'],
            ]);
        }
    }

    CustomerAddress::updateOrCreate(
        ['user_id' => $user->id],
        [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'country_id' => $country->id,
            'address' => $request->input('address'),
            'apartment' => $request->input('apartment', null),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
        ]
    );

    if ($request->input('payment_method') === 'card') {
        return redirect()->route('front.checkout.payment', ['order' => $order->id]);
    }
     invoiceEmail($order->id);
    session()->forget('cart');
    session()->forget('discount');
    session()->forget('coupon_code');

    return response()->json([
        'message' => 'Order placed successfully!',
        'redirect_url' => route('front.thankyou', ['first_name' => $request->input('first_name'), 'order_id' => $order->id])
    ]);

}



    public function thankyou(Request $request)
    {
        $firstName = $request->input('first_name');
        $orderId = $request->input('order_id');
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();

        return view('front.thankyou', compact('firstName', 'orderId', 'categories', 'subcategories'));
    }
}
