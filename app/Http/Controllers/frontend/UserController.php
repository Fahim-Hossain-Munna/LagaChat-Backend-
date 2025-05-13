<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return $this->json('error', ['message' => 'Unauthenticated'], 401);
        }

        $users = User::where('id', '!=', $user->id)
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get();


        return $this->json('success', ['users' => $users]);
    }
}
