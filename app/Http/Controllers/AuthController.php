<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST /api/register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:users,username',
            'password'    => 'required|string|min:8|confirmed',
            'teacherCode' => 'nullable|string',
        ]);

        // Check teacher code logic
        $checkCode = User::where('teacherCode', $request->teacherCode)->first();
        $userType  = $request->teacherCode === 'math@2025';

        if (! $userType && ! $checkCode) {
            return response(['message' => 'Invalid Teacher Code'], 404);
        }

        // Prepare user data
        $userData = [
            'name'      => $validated['name'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']), // <-- important!
            'isTeacher' => $userType,
        ];

        if (! $userType) {
            $userData['teacherCode'] = $request->teacherCode;
        }

        // Create user
        $user = User::create($userData);
        Auth::login($user);
        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;
        $this->storeUserDevice();
        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }


    // POST /api/login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $validated['username'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        Auth::login($user);
        $this->storeUserDevice();
        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }


    // POST /api/logout (optional)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
    function storeUserDevice()
    {
        UserDevice::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'device_type' => request()->header('Device-Type', 'unknown'),
            'device_model' => request()->header('Device-Model', 'unknown'),
            'os_version' => request()->header('OS-Version', 'unknown'),
            'app_version' => request()->header('App-Version', 'unknown'),
        ]);
    }
}
