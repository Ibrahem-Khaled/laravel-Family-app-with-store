<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::with(['subCategory', 'user'])->get();
        $subCategories = SubCategory::all();
        $users = User::all();
        return view('dashboard.contents.index', compact('contents', 'subCategories', 'users'));
    }

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
            'images' => 'nullable|array', // تغيير النوع إلى array
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // تأكد من أن الملفات هي صور
            'quantity' => 'nullable|integer',
        ]);

        // رفع الصور وتخزينها
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('content_images', 'public');
            }
        }

        // إنشاء تسجيل جديد في قاعدة البيانات
        Content::create([
            'sub_category_id' => $request->sub_category_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'colors' => $request->colors,
            'sizes' => $request->sizes,
            'images' => json_encode($imagePaths), // حفظ مسارات الصور كـ JSON
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('contents.index')->with('success', 'Content created successfully.');
    }

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
            'images' => 'nullable|array', // تغيير النوع إلى array
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // يمكن أن يكون الملف غير مطلوب في التحديث
            'quantity' => 'nullable|integer',
        ]);

        // إذا تم رفع صور جديدة
        $imagePaths = json_decode($content->images, true) ?? [];
        if ($request->hasFile('images')) {
            // حذف الصور القديمة إذا كانت موجودة
            foreach ($imagePaths as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            // رفع الصور الجديدة
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('content_images', 'public');
            }
        }

        $content->update([
            'sub_category_id' => $request->sub_category_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'colors' => $request->colors,
            'sizes' => $request->sizes,
            'images' => json_encode($imagePaths), // حفظ مسارات الصور كـ JSON
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
    }

    public function destroy(Content $content)
    {
        // حذف الصور المرتبطة إذا كانت موجودة
        $imagePaths = json_decode($content->images, true) ?? [];
        foreach ($imagePaths as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $content->delete();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    }
}
