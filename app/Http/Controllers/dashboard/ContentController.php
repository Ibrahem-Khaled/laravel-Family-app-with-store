<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::with(['subCategory', 'user'])->get();
        $subCategories = SubCategory::all();
        $users = User::all();
        return view('dashboard.contents.index', compact('contents', 'subCategories', 'users'));
    }

    // Store a new content
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:article,product',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'colors' => 'nullable|json',
            'sizes' => 'nullable|json',
            'images' => 'nullable|json',
            'quantity' => 'nullable|integer',
        ]);

        Content::create($request->all());

        return redirect()->route('contents.index')->with('success', 'Content created successfully.');
    }

    // Update a content
    public function update(Request $request, Content $content)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:article,product',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'colors' => 'nullable|json',
            'sizes' => 'nullable|json',
            'images' => 'nullable|json',
            'quantity' => 'nullable|integer',
        ]);

        $content->update($request->all());

        return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
    }

    // Delete a content
    public function destroy(Content $content)
    {
        $content->delete();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    }
}
