<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\HistoryFamily;
use Illuminate\Http\Request;

class HistoryFamilyController extends Controller
{
    public function index()
    {
        $historyFamilies = HistoryFamily::all();
        return view('dashboard.history_families', compact('historyFamilies'));
    }

    /**
     * حفظ سجل جديد.
     */
    public function store(Request $request)
    {
        $family = HistoryFamily::latest()->first();
        if ($family) {
            return redirect()->back()
                ->with('error', 'لا يمكن إضافة سجل جديد، يجب حذف السجل الحالي أولاً.');
        }
        // التحقق من البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|json',
        ]);

        // إنشاء سجل جديد
        HistoryFamily::create($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('history-families.index')
            ->with('success', 'تم إنشاء السجل بنجاح.');
    }

    /**
     * تحديث سجل موجود.
     */
    public function update(Request $request, $id)
    {
        // التحقق من البيانات
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|json',
        ]);

        // البحث عن السجل المطلوب
        $historyFamily = HistoryFamily::findOrFail($id);

        // تحديث السجل
        $historyFamily->update($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('history-families.index')
            ->with('success', 'تم تحديث السجل بنجاح.');
    }

    /**
     * حذف سجل.
     */
    public function destroy($id)
    {
        // البحث عن السجل المطلوب
        $historyFamily = HistoryFamily::findOrFail($id);

        // حذف السجل
        $historyFamily->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('history-families.index')
            ->with('success', 'تم حذف السجل بنجاح.');
    }
}
