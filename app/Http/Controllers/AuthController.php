<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function registrationUser(AuthRequest $request)
    {
        if(User::where('email', $request->email)->first()) {
            return response(array(
                'message' => 'данный email занят',
            ), 403);
        }
        $data = User::createUser($request);
        return response()->json([
            "success" => true,
            "data" => [
                "token" => $data->api_token
            ],
        ]);
    }

    public function loginApi(AuthRequest $request)
    {
        if(User::where('email', $request->get('email'))->exists()) {
            $user = User::where('email', $request->get('email'))->first();
            $auth = Hash::check($request->get('password'), $user->password);
            if($user && $auth) {
                return response()->json([
                    "success" => true,
                    "data" => [
                        "token" => $user->api_token
                    ],
                ]);
            }
        }
        return response(array(
            'message' => 'Unauthorized, нет такой комбинации email & pass',
        ), 401);
    }
}
