<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }

    // Public method to get all categories
    public function allcategory()
    {
        $categories = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'categories' => $categories,
        ]);
    }


    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Category Found.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'No Category Found.'
            ], 404);
        }

        $validatedData = $request->validate([
            'meta_title' => ['required', 'string', 'max:191'],
            // Ensure the slug is unique, but ignore the current category's slug
            'name' => ['required', 'string', 'max:191'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_descrip' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        // Manually handle the data to generate slug
        $categoryData = $request->only(['meta_title', 'name', 'meta_keyword', 'meta_descrip', 'description']);
        $categoryData['slug'] = Str::slug($validatedData['name']);

        // Handle status separately
        $categoryData['status'] = $request->boolean('status');

        $category->update($categoryData);

        return response()->json([
            'status' => 200,
            'message' => 'Category Updated Successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meta_title' => ['required', 'string', 'max:191'],
            'name' => ['required', 'string', 'max:191', 'unique:categories,name'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_descrip' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $category = new Category;
        $category->meta_title = $validatedData['meta_title'];
        $category->slug = Str::slug($validatedData['name']);
        $category->name = $validatedData['name'];
        $category->meta_keyword = $validatedData['meta_keyword'] ?? null;
        $category->meta_descrip = $validatedData['meta_descrip'] ?? null;
        $category->description = $validatedData['description'] ?? null;
        $category->status = $request->boolean('status');
        $category->save();

        return response()->json([
            'status' => 201, // Use 201 for resource creation
            'message' => 'Category Added Successfully',
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Category Found.'
            ]);
        }
    }
}
