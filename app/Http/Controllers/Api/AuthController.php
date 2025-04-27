<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
     // Register a new user
     public function register(Request $request)
     {
         // Validate the request
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8|confirmed',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         // Create the user
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);
 
         // Return the created user
         return response()->json($user, 201);
     }
 
     // Login and generate token
     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|string|min:8',
         ]);
 
         $user = User::where('email', $request->email)->first();
 
         if (!$user || !Hash::check($request->password, $user->password)) {
             throw ValidationException::withMessages([
                 'email' => ['The provided credentials are incorrect.'],
             ]);
         }
 
         // Generate and return a Sanctum token
         $token = $user->createToken('YourAppName')->plainTextToken;
 
         return response()->json(['token' => $token]);
     }
}
