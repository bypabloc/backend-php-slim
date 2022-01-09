<?php

namespace App\Middleware\Validation\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;

class SignIn
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'user' => ['required', 'string', 'min:3', 'max:20'],
                'password' => ['required', 'string', 'min:6', 'max:50'],
                'remember_me'=>['boolean'],
            ]);
    
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