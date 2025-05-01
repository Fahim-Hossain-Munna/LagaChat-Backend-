<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            $user = JWTAuth::user();

            return $this->json('login successfull', ['token' => $token, 'user' => $user]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }
    public function register(RegisterRequest $request)
    {
        try {
            $userExists = User::where('email', $request->email)->exists();

            if ($userExists) {
                return response()->json(['error' => 'user already exists'], 401);
            }
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            $token = JWTAuth::fromUser($user);

            return $this->json('register successfull', ['token' => $token, 'user' => $user]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'register failed'], 500);
        }
    }
}
