<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', 'yes')->take(8)->get();
        $latestProducts = Product::latest()->take(8)->get();
        $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get();


        return view('front.home', compact('featuredProducts', 'latestProducts', 'categories', 'subcategories'));
    }
    public function show($id, Request $request)
    {
        try {
            $featuredProducts = Product::where('is_featured', 'yes')->take(8)->get();
            $latestProducts = Product::latest()->take(8)->get();
            $categories = Category::where('showathome', '1')->take(8)->get(); // Fetch categories here
            $subcategories = SubCategory::where('showathome', '1')->take(8)->get();
            $product = Product::findOrFail($id);
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('sub_category_id', $product->sub_category_id)
                ->where('id', '!=', $product->id)
                ->take(10)
                ->get();

            // Get selected size and color if they exist
            $selectedSize = $request->input('size', null);
            $selectedColor = $request->input('color', null);

            return view('front.show', compact('product', 'featuredProducts', 'latestProducts', 'categories', 'subcategories', 'relatedProducts', 'selectedSize', 'selectedColor'));
        } catch (\Exception $e) {
            return view('front.show');
        }
    }


    public function getSubcategories(Request $request)
{
    $categoryId = $request->get('category_id');
    $subcategories = SubCategory::where('category_id', $categoryId)->pluck('name', 'id')->toArray();
    return response()->json($subcategories);
}
}
