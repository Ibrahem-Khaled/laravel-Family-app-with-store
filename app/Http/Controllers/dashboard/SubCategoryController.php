<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->get();
        $categories = Category::all();
        return view('dashboard.sub_categories.index', compact('subCategories', 'categories'));
    }

    // Store a new sub-category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sub_categories,slug',
            'image' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        SubCategory::create($request->all());

        return redirect()->route('sub-categories.index')->with('success', 'Sub-category created successfully.');
    }

    // Update a sub-category
    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sub_categories,slug,' . $subCategory->id,
            'image' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategory->update($request->all());

        return redirect()->route('sub-categories.index')->with('success', 'Sub-category updated successfully.');
    }

    // Delete a sub-category
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('sub-categories.index')->with('success', 'Sub-category deleted successfully.');
    }
}
