<?php

namespace App\Middleware\Validation\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;
use App\Middleware\Validation\Rule\IsDate;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'id' => ['required', 'integer', new Exist('users', 'id')],
                'email' => ['string', 'email', new Unique('users', 'email')],
                'nickname' => ['string', new Unique('users', 'nickname')],
                'sex'=>['required','integer', 'between:1,3'],
                'birthday'=>['required','date','before:today'],
                'password' => ['string', 'min:6', 'max:50'],
                'passwordConfirmation' => ['string', 'min:6', 'max:50', 'same:password', 'required_with:password'],
                'role_id' => ['integer', new Exist('roles', 'id')],
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