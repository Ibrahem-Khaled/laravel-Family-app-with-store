<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\HistoryFamily;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class familyController extends Controller
{
    public function index()
    {
        $familyHistory = HistoryFamily::latest()->first();
        $families = User::where('role', 'family')->get();
        return response()->json([
            'status' => 'success',
            'familyHistory' => $familyHistory,
            'families' => $families,
        ]);
    }
    public function getFamily($userId)
    {
        // تحميل المستخدم مع المنشورات المرتبطة به
        $user = User::findOrFail($userId);

        // تحميل المنشورات مع الصور والفيديوهات
        $user->load([
            'posts' => function ($query) {
                $query->select('id', 'user_id', 'title', 'description', 'images', 'videos', 'status', 'created_at');
            }
        ]);

        // تحويل الصور والفيديوهات إلى روابط كاملة
        $user->posts->each(function ($post) {
            if ($post->images) {
                $post->images = array_map(function ($image) {
                    return asset($image); // استخدام المسار المخزن كما هو
                }, json_decode($post->images, true));
            }

            if ($post->videos) {
                $post->videos = array_map(function ($video) {
                    return asset($video); // استخدام المسار المخزن كما هو
                }, json_decode($post->videos, true));
            }
        });

        return response()->json($user);
    }
    public function storePostInUserFamily(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|json',
            'videos' => 'nullable|json',
            'status' => 'nullable|in:draft,published,banned',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // إنشاء المنشور
        $post = Post::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'images' => $request->images,
            'videos' => $request->videos,
            'status' => $request->status ?? 'published',
        ]);

        return response()->json($post, 201);
    }
}
