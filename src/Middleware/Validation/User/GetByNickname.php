<?php

namespace App\Middleware\Validation\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule;

use App\Model\User;

class GetByNickname
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $args = $request->getAttribute('args');

        $validators = [
            'nickname' => [
                'required', 
                'string',
            ],
        ];

        try {
            $validator = new Validator();

            $validator->validate($args, $validators);

            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }

            $user = User::where('nickname', $validator->data['nickname'])->where('is_active', 1);

            if(!$user->first()){
                $response = new Response();
                return $this->response($response, 404, [
                    'errors' => [
                        'user' => 'User not found',
                    ],
                ]);
            }

            $validator->data['user'] = $user;

            $request = $request->withAttribute('args', $validator->data);
            
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