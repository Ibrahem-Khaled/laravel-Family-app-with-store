<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function index()
    {
        $audioFiles = Audio::with(['subCategory', 'user'])->get();
        $subCategories = SubCategory::all();
        $users = User::all();
        return view('dashboard.audio.index', compact('audioFiles', 'subCategories', 'users'));
    }

    // Store a new audio file
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:mp3,wav', // تأكد من أن الملف هو ملف صوتي
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // رفع الملف وتخزينه
        $filePath = $request->file('file')->store('audio_files', 'public');

        // إنشاء تسجيل جديد في قاعدة البيانات
        Audio::create([
            'sub_category_id' => $request->sub_category_id,
            'user_id' => $request->user_id,
            'title' => $request->title,
            'file' => $filePath, // حفظ مسار الملف
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return redirect()->route('audio.index')->with('success', 'Audio file created successfully.');
    }

    // Update an audio file
    public function update(Request $request, Audio $audio)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:mp3,wav', // يمكن أن يكون الملف غير مطلوب في التحديث
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // إذا تم رفع ملف جديد
        if ($request->hasFile('file')) {
            // حذف الملف القديم إذا كان موجودًا
            Storage::disk('public')->delete($audio->file);

            // رفع الملف الجديد
            $filePath = $request->file('file')->store('audio_files', 'public');
            $audio->file = $filePath;
        }

        $audio->update([
            'sub_category_id' => $request->sub_category_id,
            'user_id' => $request->user_id,
            'title' => $request->title,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return redirect()->route('audio.index')->with('success', 'Audio file updated successfully.');
    }

    // Delete an audio file
    public function destroy(Audio $audio)
    {
        // حذف الملف الصوتي
        Storage::disk('public')->delete($audio->file);
        $audio->delete();
        return redirect()->route('audio.index')->with('success', 'Audio file deleted successfully.');
    }
}
