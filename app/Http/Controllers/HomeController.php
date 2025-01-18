<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Category;
use App\Models\Content;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalSubCategories = SubCategory::count();
        $totalContents = Content::count();
        $totalAudioFiles = Audio::count();

        // إحصائيات تفاعل المستخدمين (مثال: عدد المشاهدات)
        $totalViews = Content::sum('views');

        // بيانات للرسم البياني
        $categories = Category::withCount('subCategories')->get();
        $categoryNames = $categories->pluck('name');
        $subCategoryCounts = $categories->pluck('sub_categories_count');

        return view('dashboard.index', compact(
            'totalUsers',
            'totalCategories',
            'totalSubCategories',
            'totalContents',
            'totalAudioFiles',
            'totalViews',
            'categoryNames',
            'subCategoryCounts'
        ));
    }
}
