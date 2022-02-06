<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Session;

class SessionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $post = new Session;

        $post->title = 'prueba de titulo';
        $post->body = 'prueba de body';
        $post->slug = 'prueba de slug';
        
        $post->save();

        return response()->json([
            'session' => Session::all(),
        ]);
    }
}
