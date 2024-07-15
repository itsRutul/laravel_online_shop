<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'subCategories', 'brands'));
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'sku' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'qty' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'is_featured' => 'required|in:yes,no',
            'size' => 'nullable|array',
            'size.*' => 'string|max:255',
            'color' => 'nullable|array',
            'color.*' => 'string|max:255',
        ]);

        $images = [];
        if ($files = $request->file('images')) {
            foreach ($files as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('product_images'), $name);
                $images[] = $name;
            }
        }

        $productData = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'],
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'],
            'qty' => $validated['qty'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'brand_id' => $validated['brand_id'],
            'is_featured' => $validated['is_featured'],
            'image' => json_encode($images),
        ];

        if (!empty($validated['size'])) {
            $productData['size'] = json_encode($validated['size']);
        }

        if (!empty($validated['color'])) {
            $productData['color'] = json_encode($validated['color']);
        }

        $product = Product::create($productData);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
    }
}


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();


        return view('admin.products.edit', compact('product', 'categories', 'subCategories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products,slug,' . $id,
                'description' => 'required|string',
                'images' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'sku' => 'required|string|max:255|unique:products,sku,' . $id,
                'barcode' => 'nullable|string|max:255',
                'qty' => 'required|integer|min:0',
                'status' => 'required|boolean',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'is_featured' => 'required|in:yes,no',
                'size' => 'nullable|array',
                'size.*' => 'string|max:255',
                'color' => 'nullable|array',
                'color.*' => 'string|max:255',
            ]);

            $productData = [
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => $validated['description'],
                'price' => $validated['price'],
                'compare_price' => $validated['compare_price'],
                'sku' => $validated['sku'],
                'barcode' => $validated['barcode'],
                'qty' => $validated['qty'],
                'status' => $validated['status'],
                'category_id' => $validated['category_id'],
                'sub_category_id' => $validated['sub_category_id'],
                'brand_id' => $validated['brand_id'],
                'is_featured' => $validated['is_featured'],
            ];

            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('product_images'), $name);
                    $images[] = $name;
                }
                $productData['image'] = json_encode($images);
            }

            if (!empty($validated['size'])) {
                $productData['size'] = json_encode($validated['size']);
            }

            if (!empty($validated['color'])) {
                $productData['color'] = json_encode($validated['color']);
            }

            $product->update($productData);

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }


public function deleteImage(Request $request, $id)
{
    try {
        $product = Product::findOrFail($id);
        $imageName = $request->input('image');

        $images = json_decode($product->image, true);
        if (($key = array_search($imageName, $images)) !== false) {
            unset($images[$key]);
            $product->image = json_encode(array_values($images)); // Reindex the array
            $product->save();

            // Delete the image from the server
            $imagePath = public_path('product_images/' . $imageName);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}



    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete images from the folder
            $images = json_decode($product->image, true); // Decode JSON to array
            foreach ($images as $image) {
                $imagePath = public_path('product_images/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the file from the server
                }
            }

            // Now delete the product from the database
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return view('admin.products.show', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
    }
}
