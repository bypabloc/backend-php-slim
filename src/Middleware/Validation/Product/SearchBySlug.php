<?php

namespace App\Middleware\Validation\Product;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule;

use App\Model\Product;

class SearchBySlug
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getAttribute('params');

        $validators = [
            'p' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9\s]+$/u',
            ],
        ];

        try {
            $validator = new Validator();

            $validator->validate($params, $validators);

            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }

            $request = $request->withAttribute('params', $params);
            
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