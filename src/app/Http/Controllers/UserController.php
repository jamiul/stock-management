<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    // show user
    public function show(User $user)
    {
        Redis::set('name', 'Taylor');
        $name = Redis::get('name');
        return $name;
        // return response()->json($user, Response::HTTP_OK);
    }

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
