<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    // login method
    public function login(LoginRequest $request)
    {
        $data = $request->all();

        // check email
        $user = User::where('email', $data['email'])->first();

        // check password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Credentials not matched!'
            ], Response::HTTP_UNAUTHORIZED);
        }
        // dd($user);
        $token = $user->createToken('private')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response);
    }

    // logout method
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }
}
