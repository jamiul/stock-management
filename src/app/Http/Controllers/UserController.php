<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    // register new user
    public function register(UserRequest $request): object
    {
        $data = $request->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return response()->json($user, Response::HTTP_CREATED);
    }
}
