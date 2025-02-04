<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Content;
use App\Models\SubCategory;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function getCategoriesContentOnly($type = 'article')
    {
        // جلب الفئات الرئيسية التي تحتوي على فئات فرعية لها محتوى من النوع المحدد
        $categories = Category::whereHas('subcategories.contents', function ($query) use ($type) {
            $query->where('type', $type);
        })->with([
                    'subcategories' => function ($query) use ($type) {
                        // جلب الفئات الفرعية التي تحتوي على محتوى من النوع المحدد
                        $query->whereHas('contents', function ($query) use ($type) {
                            $query->where('type', $type);
                        });
                    }
                ])->get();

        // إذا لم يتم العثور على فئات
        if ($categories->isEmpty()) {
            return response()->json(['message' => "No categories found with $type content in subcategories"], 404);
        }

        // إضافة رابط الصورة الكاملة لكل فئة رئيسية وفرعية
        $categories->transform(function ($category) {
            if ($category->image) {
                $category->image_url = Storage::url($category->image);
            } else {
                $category->image_url = null;
            }

            // إضافة رابط الصورة الكاملة للفئات الفرعية
            $category->subcategories->transform(function ($subcategory) {
                if ($subcategory->image) {
                    $subcategory->image_url = Storage::url($subcategory->image);
                } else {
                    $subcategory->image_url = null;
                }
                return $subcategory;
            });

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

    public function getContents()
    {
        $contents = Content::limit(5)->with(['subCategory', 'user'])
            ->where('type', 'article')
            ->latest()
            ->get();
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


    public function getProducts()
    {
        $products = Content::limit(5)->with(['subCategory', 'user'])
            ->where('type', 'product')
            ->latest()
            ->get();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        //إضافة رابط الصورة الكاملة للمحتوى
        $products->transform(function ($product) {
            if ($product->images) {
                $product->images = json_decode($product->images);
                $product->images = array_map(function ($image) {
                    return Storage::url($image);
                }, $product->images);
            } else {
                $product->images = [];
            }
            return $product;
        });

        return response()->json($products);
    }

    public function getContent($id)
    {
        $content = Content::with(['subCategory', 'user'])->find($id);
        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        //إضافة رابط الصورة الكاملة للمحتوى
        if ($content->images) {
            $content->images = json_decode($content->images);
            $content->images = array_map(function ($image) {
                return Storage::url($image);
            }, $content->images);
        } else {
            $content->images = null;
        }

        return response()->json($content);
    }

    public function getSubCategory($id)
    {
        $subCategory = SubCategory::with('contents.user')->find($id);
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

    public function getAudios($id = null)
    {
        if (is_null($id)) {
            // إذا لم يتم تقديم id، قم بإرجاع جميع الـ Audios مع العلاقات
            $audios = Audio::with('subCategory', 'user')->get();
            return response()->json($audios);
        }

        // إذا تم تقديم id، قم بالبحث عن الـ SubCategory والـ Audios المرتبطة بها
        $subCategory = SubCategory::with('audios')->find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'No subcategory found'], 404);
        }

        if ($subCategory->audios->isEmpty()) {
            return response()->json(['message' => 'No audios found for this subcategory'], 404);
        }

        return response()->json($subCategory->audios);
    }

    public function getSubCategoriesAudios()
    {
        $subCategories = SubCategory::whereHas('audios')->with('audios')->get();
        if ($subCategories->isEmpty()) {
            return response()->json(['message' => 'No sub-categories with audios found'], 404);
        }

        return response()->json($subCategories);
    }

    public function getSubscriptions(User $user = null)
    {
        $subscriptions = Subscription::all();

        if ($user) {

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $userSubscription = $user->subscriptions()->latest()->first();

            if (!$userSubscription) {
                return response()->json($subscriptions);
            }

            return response()->json($userSubscription);
        }

        return response()->json($subscriptions);
    }
}