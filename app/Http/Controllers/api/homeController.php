<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], 404);
        }

        // إضافة رابط الصورة الكاملة لكل فئة
        $categories->transform(function ($category) {
            if ($category->image) {
                $category->image_url = Storage::url($category->image);
            } else {
                $category->image_url = null;
            }
            return $category;
        });

        return response()->json($categories);
    }

    public function getCategory($id)
    {
        $category = Category::with('subCategories')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // إضافة رابط الصورة الكاملة للفئة
        if ($category->image) {
            $category->image_url = Storage::url($category->image);
        } else {
            $category->image_url = null;
        }

        // إضافة روابط الصور الكاملة للفئات الفرعية
        $category->subCategories->transform(function ($subCategory) {
            if ($subCategory->image) {
                $subCategory->image_url = Storage::url($subCategory->image);
            } else {
                $subCategory->image_url = null;
            }
            return $subCategory;
        });

        return response()->json($category);
    }

    public function getRandomContents()
    {
        $contents = Content::inRandomOrder()->limit(5)->with(['subCategory', 'user'])->get();
        if ($contents->isEmpty()) {
            return response()->json(['message' => 'No contents found'], 404);
        }

        // إضافة روابط الصور الكاملة للمحتويات
        $contents->transform(function ($content) {
            if ($content->images) {
                $content->images = json_decode($content->images);
                $content->images = array_map(function ($image) {
                    return Storage::url($image);
                }, $content->images);
            } else {
                $content->images = [];
            }
            return $content;
        });

        return response()->json($contents);
    }

    public function getContent($id)
    {
        $content = Content::with(['subCategory', 'user'])->find($id);
        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        // إضافة روابط الصور الكاملة للمحتوى
        if ($content->images) {
            $content->images = json_decode($content->images);
            $content->images = array_map(function ($image) {
                return Storage::url($image);
            }, $content->images);
        } else {
            $content->images = [];
        }

        return response()->json($content);
    }

    public function getSubCategory($id)
    {
        $subCategory = Category::with('contents')->find($id);
        if (!$subCategory) {
            return response()->json(['message' => 'Sub-category not found'], 404);
        }

        // إضافة رابط الصورة الكاملة للفئة الفرعية
        if ($subCategory->image) {
            $subCategory->image_url = Storage::url($subCategory->image);
        } else {
            $subCategory->image_url = null;
        }

        // إضافة روابط الصور الكاملة للمحتويات المرتبطة
        $subCategory->contents->transform(function ($content) {
            if ($content->images) {
                $content->images = json_decode($content->images);
                $content->images = array_map(function ($image) {
                    return Storage::url($image);
                }, $content->images);
            } else {
                $content->images = [];
            }
            return $content;
        });

        return response()->json($subCategory);
    }

    public function getAudios()
    {
        $audios = Audio::with(['subCategory', 'user'])->get();
        if ($audios->isEmpty()) {
            return response()->json(['message' => 'No audios found'], 404);
        }

        // إضافة رابط الصورة الكاملة لكل ملف صوتي
        $audios->transform(function ($audio) {
            if ($audio->image) {
                $audio->file_url = Storage::url($audio->file);
            } else {
                $audio->file_url = null;
            }
            return $audio;
        });

        return response()->json($audios);
    }
}