<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Session;
use App\Models\User;

use App\Services\JWT;

class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $session_user_id = Session::whereNotNull('delete_at')->pluck('user_id')->all();
        
        $user = User::whereNotIn('id',$session_user_id)->get()->first();

        $token = JWT::GenerateToken($user->uuid, $user->id);

        return [
            'token' => $token,
            'user_id' => $user->id,
            'expired_at' => now()->addMinutes(30),
        ];
    }
}
