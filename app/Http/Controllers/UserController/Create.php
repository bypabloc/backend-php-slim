<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $user = new User;

        $user->nickname = $body['nickname'];
        $user->email = $body['email'];
        $user->sex = $body['sex'];
        $user->birthday = $body['birthday'];
        $user->password = $body['password'];
        $user->role_id = $body['role_id'];

        if(isset($body['is_active'])){
            $user->is_active = $body['is_active'];
        }
        if(isset($body['image'])){
            $user->image = $body['image'];
            $user->creatingCustom();
        }

        $user->save();

        $data = [
            'user' => [
                'nickname' => $user->nickname,
                'email' => $user->email,
                'token' => $user->token,
            ],
        ];

        $res = [
            'message' => 'User created successfully.',
            'data' => $data,
        ];

        return response()->json($res, 201);
    }
}
