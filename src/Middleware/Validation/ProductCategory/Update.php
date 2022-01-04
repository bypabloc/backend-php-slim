<?php

namespace App\Middleware\Validation\ProductCategory;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\ItemOwner;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        $validators = [
            'id' => ['required', 'integer', new Exist('products_categories', 'id')],
            'name' => ['string', 'min:3', 'max:20'],
            'parent_id' => ['integer', new Exist('products_categories', 'id')],
            'is_active' => ['boolean'],
        ];
        if ($check_permission_admin) {
            $validators['user_id'] = ['integer', new Exist('users', 'id')];
        }else{
            $user_id = $session->user_id;
            $body['user_id'] = $user_id;
            $validators['id'] = [new Exist(
                table: 'products_categories',
                column: 'id',
                owner: 'user_id',
            )];
        }

        try {
            $validator = new Validator();

            $validator->validate($body, $validators);
    
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