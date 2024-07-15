<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.dashboard', compact('admin'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }



    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return view('front.products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Product not found.');
        }
    }
}


