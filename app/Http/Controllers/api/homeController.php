<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], 404);
        }
        return response()->json($categories);
    }

    public function getCategory($id)
    {
        $category = Category::with('subCategories')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    public function getRandomContents()
    {
        $contents = Content::inRandomOrder()->limit(5)->with(['subCategory', 'user'])->get();
        if ($contents->isEmpty()) {
            return response()->json(['message' => 'No contents found'], 404);
        }
        return response()->json($contents);
    }

    public function getContent($id)
    {
        $content = Content::with(['subCategory', 'user'])->find($id);
        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }
        return response()->json($content);
    }

    public function getSubCategory($id)
    {
        $subCategory = Category::with('contents')->find($id);
        if (!$subCategory) {
            return response()->json(['message' => 'Sub-category not found'], 404);
        }
        return response()->json($subCategory);
    }

    public function getAudios()
    {
        $audios = Audio::with(['subCategory', 'user'])->get();
        if ($audios->isEmpty()) {
            return response()->json(['message' => 'No audios found'], 404);
        }
        return response()->json($audios);
    }
}