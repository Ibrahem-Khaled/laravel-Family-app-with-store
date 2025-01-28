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
        $family = User::findOrFail($userId)->load('posts');
        return response()->json($family);
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
