<?php

namespace App\Middleware\Validation\MyProfile;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');

        $user_id = $session->user->id;

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'email' => ['string', 'email', new Unique(
                    table: 'users',
                    column: 'email',
                    id: $user_id,
                )],
                'nickname' => ['string', new Unique(
                    table: 'users',
                    column: 'nickname',
                    id: $user_id,
                )],
                
                'is_active' => ['boolean'],
                'image' => [new IsBase64(
                    types: ['png','jpg', 'jpeg', 'gif'],
                    size: 2048,
                )],
            ], );
    
            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }
            
            $request = $request->withAttribute('body', $validator->data);
            
            return $handler->handle($request);

        } catch (\Throwable $th) {
            $response = new Response();
            $response = $this->response($response, 500, [
                'errors' => $th,
            ]);
        }

        return $response;
    }
}