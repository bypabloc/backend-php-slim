<?php

namespace App\Middleware\Validation\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;

class Create
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = (array) $request->getParsedBody(); // get all body params

        try {
            $validator = new Validator();

            $validator->validate($params, [
                'email' => ['required', 'string', 'email', new Unique('users', 'email')],
                'nickname' => ['required', 'string', 'min:3', 'max:20'],
                'password' => ['required', 'string', 'min:6', 'max:50'],
                'passwordConfirmation' => ['required', 'string', 'min:6', 'max:50', 'same:password'],
            ], [
                'required' => 'The :attribute field is required.',
                'email' => 'The :attribute field must type email.',
                'min' => 'The :attribute field must greater than :min.',
                'max' => 'The :attribute field must less than :max.',
                'same' => 'The :attribute field must same :same.',
            ]);
    
            if($validator->isValid()){
                $request = $request->withParsedBody([
                    'data' => $validator->data,
                ]);
                return $handler->handle($request);
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