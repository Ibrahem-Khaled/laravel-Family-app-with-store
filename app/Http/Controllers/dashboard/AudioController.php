<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;

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
            'file' => 'required|string',
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Audio::create($request->all());

        return redirect()->route('audio.index')->with('success', 'Audio file created successfully.');
    }

    // Update an audio file
    public function update(Request $request, Audio $audio)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'file' => 'required|string',
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $audio->update($request->all());

        return redirect()->route('audio.index')->with('success', 'Audio file updated successfully.');
    }

    // Delete an audio file
    public function destroy(Audio $audio)
    {
        $audio->delete();
        return redirect()->route('audio.index')->with('success', 'Audio file deleted successfully.');
    }
}
