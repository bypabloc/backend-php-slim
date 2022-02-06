<?php

namespace App\Middleware\Validation\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\IsDate;

class SignUp
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'email' => ['required', 'string', 'email', 'max:50', new Unique(
                    table: 'users',
                    column: 'email',
                )],
                'nickname' => ['required', 'string', 'max:50', new Unique(
                    table: 'users',
                    column: 'email',
                )],
                'sex'=>['required','integer', 'between:1,3'],
                'birthday'=>['required','date','before:today'],
                'password' => ['required', 'string', 'min:6', 'max:50'],
                'passwordConfirmation' => ['required', 'string', 'min:6', 'max:50', 'same:password'],
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