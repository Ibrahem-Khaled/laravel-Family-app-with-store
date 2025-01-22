<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected function response($message, $data = [], $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function authenticateUser($phone, $password)
    {
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function loginByEmailOrPhone(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|min:6',
        ]);

        if ($validate->fails()) {
            return $this->response('Validation failed', ['errors' => $validate->errors()], 422);
        }

        try {
            $user = $this->authenticateUser($request->phone, $request->password);

            if (!$user) {
                return $this->response('Invalid credentials', [], 401);
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return $this->response('Login successful', ['user' => $user, 'token' => $token]);

        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return $this->response('Login failed', ['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ], [
            'phone.unique' => 'رقم الهاتف مستخدم من قبل.',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
        ]);

        if ($validator->fails()) {
            return $this->response('Validation failed', ['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $path = $avatar->store('avatars', 'public');
                $user->avatar = $path;
                $user->save();
            }

            $token = $user->createToken('api_token')->plainTextToken;
            $user->api_token = $token;
            $user->save();

            return $this->response('Registration successful', ['user' => $user, 'token' => $token], 201);

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return $this->response('Registration failed', ['error' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return $this->response('Unauthenticated', [], 401);
            }

            $user->tokens()->delete();

            return $this->response('Logout successful');

        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return $this->response('Logout failed', ['error' => $e->getMessage()], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            // جلب التوكن من الـ headers
            $token = $request->bearerToken();

            if (!$token) {
                return $this->response('Unauthenticated', [], 401);
            }

            // البحث عن المستخدم باستخدام التوكن
            $user = User::where('api_token', $token)->first();

            if (!$user) {
                return $this->response('User not found', [], 404);
            }

            return $this->response('User data', ['user' => $user]);

        } catch (\Exception $e) {
            Log::error('Failed to get user data: ' . $e->getMessage());
            return $this->response('Failed to get user data', ['error' => $e->getMessage()], 500);
        }
    }
}