<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Fetch necessary data for the shop page
        $featuredProducts = Product::where('is_featured', 'yes')->take(8)->get();
        $latestProducts = Product::latest()->take(8)->get();
        $categories = Category::where('showathome', '1')->take(8)->get();
        $subcategories = SubCategory::where('showathome', '1')->take(8)->get() ?? []; // Handle null case
        
        $brands = Brand::all(); // Fetch all brands
        
        $priceRanges = [
            ['value' => '0-100', 'label' => '$0-$100'],
            ['value' => '100-200', 'label' => '$100-$200'],
            ['value' => '200-500', 'label' => '$200-$500'],
            ['value' => '500+', 'label' => '$500+'],    
        ];

        $productsQuery = Product::query();

        // Filter by subcategory if provided
        if ($request->has('sub-category')) {
            $subcategorySlug = $request->get('sub-category');
            $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
            if ($subcategory) {
                $productsQuery->where('sub_category_id', $subcategory->id);
            }
        }

     

        // Filtering by brands
        if ($request->has('brands')) {
            $brandIds = explode(',', $request->brands);
            $productsQuery->whereIn('brand_id', $brandIds);
        }

        // Filtering by price range
        if ($request->has('price_range')) {
            $priceRange = explode('-', $request->get('price_range'));
            if (count($priceRange) == 2) {
                $productsQuery->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            } else {
                $productsQuery->where('price', '>=', 500);
            }
        }

        // Filtering by categories
        if ($request->has('category')) {
            $categoryId = $request->get('category');
            $productsQuery->where('category_id', $categoryId);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->get('sort')) {
                case 'latest':
                    $productsQuery->latest();
                    break;
                case 'price_high':
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 'price_low':
                    $productsQuery->orderBy('price', 'asc');
                    break;
            }
        }

        // Fetch products with pagination
        $products = $productsQuery->paginate(12);

        return view('front.shop', compact('featuredProducts', 'latestProducts', 'categories', 'subcategories', 'brands', 'priceRanges', 'products'));
    }
}
