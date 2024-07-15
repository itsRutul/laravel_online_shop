<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.sub-category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category);
                }),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category);
                }),
            ],
            'status' => 'required|integer',
            'showathome' => 'required|integer',
        ]);
    

        $subCategory = SubCategory::create([
            'category_id' => $request->category,
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status,
            'showathome' => $request->showathome,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Subcategory created successfully.');
    }
    
    public function index(Request $request)
    {
        $subCategories = SubCategory::with('category');

        if ($request->has('table_search')) {
            $subCategories->where('name', 'LIKE', "%{$request->table_search}%");
        }

        $subCategories = $subCategories->latest()->paginate(10);

        return view('admin.sub-category.list', compact('subCategories'));
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.sub-category.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category);
                }),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category);
                }),
            ],
            'status' => 'required|integer',
            'showathome' => 'required|integer',
        ]);
    
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->update([
            'category_id' => $request->category,
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->status,
            'showathome' => $request->showathome,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()->route('sub-categories.index')->with('success', 'Subcategory deleted successfully.');
    }
   
public function subcategoriesByCategory($categoryId)
{
    // Retrieve subcategories for the specified category
    $category = Category::with('subCategories')->find($categoryId);
    
    if ($category) {
        $subcategories = $category->subCategories;
        // Return the subcategories as JSON response
        return response()->json($subcategories);
    } else {
        // Return an empty array if the category does not exist
        return response()->json([]);
    }
}
}
