<?php

namespace App\Middleware\Validation\Role;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Exist;

class AssignPermission
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'id' => ['required', 'integer', new Exist('roles', 'id')],
                'permission' => ['required', 'integer', new Exist('permissions', 'id')],
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