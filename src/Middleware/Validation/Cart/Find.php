<?php

namespace App\Middleware\Validation\Cart;

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
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        $validators = [
            'id' => ['required', 'integer', new Exist('carts', 'id')],
        ];
        if (!$check_permission_admin) {
            $user_id = $session->user_id;
            $params['user_id'] = $user_id;
            $validators['id'] = [new Exist(
                table: 'carts',
                column: 'id',
                owner: 'user_id',
            )];
        }
        // print_r($check_permission_admin ? 'true' : 'false');

        try {
            $validator = new Validator();

            $validator->validate($params, $validators);
    
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