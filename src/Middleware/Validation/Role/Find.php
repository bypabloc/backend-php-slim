<?php

namespace App\Middleware\Validation\Role;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Exist;

class Find
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getAttribute('params');

        try {
            $validator = new Validator();

            $validator->validate($params, [
                'id' => ['required', 'string', new Exist('roles', 'id')],
            ], [
                'required' => 'The :attribute field is required.',
                'email' => 'The :attribute field must type email.',
                'min' => 'The :attribute field must greater than :min.',
                'max' => 'The :attribute field must less than :max.',
                'same' => 'The :attribute field must same :same.',
            ]);
    
            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }
            
            $request = $request->withAttribute('params', $validator->data);
            
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