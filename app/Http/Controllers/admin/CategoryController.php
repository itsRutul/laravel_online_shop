<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('table_search')) {
            $query->where('name', 'LIKE', "%{$request->table_search}%");
        }

        $categories = $query->latest()->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'status' => 'required|integer',
            'showathome' => 'required|integer',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size if necessary
        ]);

        // Handle image upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $imageName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('category_images'), $imageName);
            }

        // Create the category
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imageName,
            'status' => $request->status,
            'showathome' => $request->showathome,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'status' => 'required|integer',
            'showathome' => 'required|integer',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);

        // Handle the image upload if a new file is provided
        if ($request->hasFile('file')) {
            // Delete the existing image if it exists
            if ($category->image) {
                $existingImagePath = public_path('category_images/' . $category->image);
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            // Upload the new image
            $file = $request->file('file');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('category_images'), $imageName);
        } else {
            // Keep existing image if no new image is uploaded
            $imageName = $category->image;
        }

        // Update the category
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Update slug if name is changed
            'image' => $imageName,
            'status' => $request->status,
            'showathome' => $request->showathome,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }
    public function deleteImage(Request $request, $id)
{
    try {
        $category = Category::findOrFail($id);

        $imageName = $request->input('filename');
        $imagePath = public_path('category_images/' . $imageName);

        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the file from the server
        }

        // Update the image field in the database if necessary
        if ($category->image == $imageName) {
            $category->update(['image' => null]);
        }

        return response()->json(['success' => 'Image deleted successfully.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error deleting image: ' . $e->getMessage()]);
    }
}


    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete the image from the folder
        if ($category->image) {
            $imagePath = public_path('category_images/' . $category->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Now delete the category from the database
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
