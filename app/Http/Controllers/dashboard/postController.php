<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class postController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        $users = User::where('role', 'family')->get();
        return view('dashboard.posts', compact('posts', 'users'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'user_id' => 'required|exists:users,id', // التأكد من أن المستخدم المحدد موجود
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:10240',
            'status' => 'required|in:draft,published,banned',
        ]);

        // رفع الصور
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $images[] = Storage::url($path);
            }
        }

        // رفع الفيديوهات
        $videos = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('public/videos');
                $videos[] = Storage::url($path);
            }
        }

        // إنشاء المنشور
        Post::create([
            'user_id' => $request->user_id, // استخدام المستخدم المحدد
            'title' => $request->title,
            'description' => $request->description,
            'images' => json_encode($images),
            'videos' => json_encode($videos),
            'status' => $request->status,
        ]);

        return redirect()->route('posts.index')->with('success', 'تم إنشاء المنشور بنجاح.');
    }

    public function update(Request $request, Post $post)
    {
        // التحقق من البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:10240',
            'status' => 'required|in:draft,published,banned',
        ]);

        // رفع الصور الجديدة
        $images = json_decode($post->images, true) ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/images');
                $images[] = Storage::url($path);
            }
        }

        // رفع الفيديوهات الجديدة
        $videos = json_decode($post->videos, true) ?? [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('public/videos');
                $videos[] = Storage::url($path);
            }
        }

        // تحديث المنشور
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'images' => json_encode($images),
            'videos' => json_encode($videos),
            'status' => $request->status,
        ]);

        return redirect()->route('posts.index')->with('success', 'تم تحديث المنشور بنجاح.');
    }
    public function destroy(Post $post)
    {
        // حذف الصور والفيديوهات من التخزين
        foreach (json_decode($post->images, true) ?? [] as $image) {
            Storage::delete(str_replace('/storage', 'public', $image));
        }
        foreach (json_decode($post->videos, true) ?? [] as $video) {
            Storage::delete(str_replace('/storage', 'public', $video));
        }

        // حذف المنشور
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'تم حذف المنشور بنجاح.');
    }
}
