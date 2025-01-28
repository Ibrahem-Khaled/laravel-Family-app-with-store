<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::all();
        return view('dashboard.users.index', compact('users'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'nullable|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|unique:users',
            'avatar' => 'nullable',
            'address' => 'nullable',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user,creator,family',
        ]);

        User::create($request->all());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'nullable|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'avatar' => 'nullable',
            'address' => 'nullable',
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,user,creator,family',
        ]);
        $request->merge(['password' => bcrypt($request->password)]);
        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}