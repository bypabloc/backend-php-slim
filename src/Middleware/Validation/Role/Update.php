<?php

namespace App\Middleware\Validation\Role;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Exist;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'id' => ['required', 'integer', new Exist('roles', 'id')],
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'state' => ['boolean'],
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