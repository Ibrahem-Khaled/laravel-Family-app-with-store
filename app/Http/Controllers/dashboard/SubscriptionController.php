<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('dashboard.subscriptions.index', compact('subscriptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:1',
            'max_families' => 'nullable|integer|min:1',
            'max_posts' => 'nullable|integer|min:1',
            'features' => 'nullable|json',
            'payment_url' => 'nullable|url',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subscriptions');
        }

        Subscription::create($data);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }
    public function show(Subscription $subscription)
    {
        $users = User::all(); // جميع المستخدمين لإضافتهم
        return view('dashboard.subscriptions.show', compact('subscription', 'users'));
    }
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:1',
            'max_families' => 'nullable|integer|min:1',
            'max_posts' => 'nullable|integer|min:1',
            'features' => 'nullable|json',
            'payment_url' => 'nullable|url',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subscriptions');
        }

        $subscription->update($data);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }


    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }

    public function addUser(Request $request, Subscription $subscription)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $subscription->users()->attach($request->user_id, [
            'active' => true,
            'start_at' => now(),
            'end_at' => now()->addDays($subscription->duration),
        ]);

        return redirect()->back()->with('success', 'User added to subscription successfully.');
    }

    // حذف مستخدم من الاشتراك
    public function removeUser(Request $request, Subscription $subscription)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $subscription->users()->detach($request->user_id);

        return redirect()->back()->with('success', 'User removed from subscription successfully.');
    }
}
