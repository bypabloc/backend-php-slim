<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

class Pagination
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = (array) $request->getQueryParams(); // get all params get query params
        // $params = (array) $request->getParsedBody(); // get all body params

        try {
            $validator = new Validator();

            $validator->validate($params, [
                'page' => ['required', 'integer', 'min:1'],
                'per_page' => ['required', 'integer', 'min:5'],
            ], [
                'required' => 'The :attribute field is required.',
            ]);
    
            if($validator->isValid()){
                $request = $request->withParsedBody([
                    'data' => $validator->data,
                ]);
                $response = $handler->handle($request);
            }else{
                $response = new Response();
                $response = $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
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