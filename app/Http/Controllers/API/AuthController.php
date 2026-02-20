<?php

namespace App\Http\Controllers\API; // Add \API here

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Use Laravel's built-in validation for cleaner code
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            // The 'hashed' cast on the User model handles hashing automatically.
            'password' => $validatedData['password'],
            // New users default to role 0 (user) based on the migration
        ]);

        // Create a token for the new user
        $token = $user->createToken($user->email . '_Token', [])->plainTextToken;

        // Return a successful response
        return response()->json([
            'status'   => 201,
            'success'  => true,
            'username' => $user->name,
            'message'  => 'Registered Successfully',
            'token'    => $token,
            'user'     => $user,
            'role'     => 'user', // All new registrations are 'user'
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['status' => 401, 'message' => 'Invalid Credentials'], 401);
        }

        $user = Auth::user();

        // Force cast to integer to match your tinyint database column
        if ((int)$user->role_as === 1) {
            $role = 'admin';
            $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
        } else {
            $role = 'user';
            $token = $user->createToken($user->email . '_Token', [])->plainTextToken;
        }

        return response()->json([
            'status'   => 200,
            'username' => $user->name,
            'message'  => 'Logged In Successfully',
            'token'    => $token,
            'role'     => $role,
        ], 200);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status'  => 200,
                'message' => 'Logged out successfully',
            ]);
        }

        return response()->json([
            'status'  => 401,
            'message' => 'Not Authenticated',
        ]);
    }
}
