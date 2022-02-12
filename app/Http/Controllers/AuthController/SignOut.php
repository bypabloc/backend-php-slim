<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Services\JWT;
use App\Services\Response;

use App\Model\User;

class SignOut extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $res = [
            'message' => [
                'success' => 'Logout success.',
            ],
        ];

        return Response::OK(
            message: 'User signed in successfully.',
        );
    }
}
