<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class State extends Controller
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
        $user = User::find($body['id']);

        $user->is_active = $body['is_active'];
        $user->save();

        $data = [
            'data' => [
                'user' => $user,
            ],
        ];

        $res = [
            'message' => 'User created successfully.',
            'data' => $data,
        ];

        return response()->json($res, 201);
    }
}
