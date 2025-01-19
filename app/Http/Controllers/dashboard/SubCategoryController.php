<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->get();
        $categories = Category::all();
        return view('dashboard.sub_categories.index', compact('subCategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sub_categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // تأكد من أن الملف هو صورة
            'category_id' => 'required|exists:categories,id',
        ]);

        // رفع الصورة وتخزينها
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sub_category_images', 'public');
        } else {
            $imagePath = null;
        }

        // إنشاء تسجيل جديد في قاعدة البيانات
        SubCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $imagePath, // حفظ مسار الصورة
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub-category created successfully.');
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sub_categories,slug,' . $subCategory->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // يمكن أن يكون الملف غير مطلوب في التحديث
            'category_id' => 'required|exists:categories,id',
        ]);

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($subCategory->image) {
                Storage::disk('public')->delete($subCategory->image);
            }

            // رفع الصورة الجديدة
            $imagePath = $request->file('image')->store('sub_category_images', 'public');
            $subCategory->image = $imagePath;
        }

        $subCategory->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub-category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        // حذف الصورة المرتبطة إذا كانت موجودة
        if ($subCategory->image) {
            Storage::disk('public')->delete($subCategory->image);
        }

        $subCategory->delete();
        return redirect()->route('sub-categories.index')->with('success', 'Sub-category deleted successfully.');
    }
}
