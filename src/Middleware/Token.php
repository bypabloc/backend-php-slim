<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Services\JWT;

class Token
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getHeaders();
        $headers_list = [];
        foreach ($headers as $name => $values) {
            $headers_list[$name] = implode(", ", $values);
        }

        try {
            $validator = new Validator();

            $validator->validate($headers_list, [
                'Authorization' => ['required', 'string'],
            ], [
                'required' => 'The :attribute field is required.',
                'email' => 'The :attribute field must type email.',
                'min' => 'The :attribute field must greater than :min.',
                'max' => 'The :attribute field must less than :max.',
                'same' => 'The :attribute field must same :same.',
            ]);
    
            if(!$validator->isValid()){
                $response = new Response();
                $response = $this->response($response, 401, [
                    'errors' => ['Token not found'],
                ]);
            }

            $token = $validator->data['Authorization'];

            $jwt = new JWT();
            $jwt->VerifyToken($token);
            if(!$jwt->isValid()){
                $response = new Response();
                $response = $this->response($response, 401, [
                    'errors' => $jwt->errors(),
                ]);
            }else{
                $request = $request->withParsedBody([
                    'session' => $jwt->session(),
                ]);
                return $handler->handle($request);
            }
        } catch (\Throwable $th) {
            $response = new Response();
            $response = $this->response($response, 500, [
                'errors' => $th,
            ]);
        }

        return $response;
    }
}