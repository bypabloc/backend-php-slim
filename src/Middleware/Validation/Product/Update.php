<?php

namespace App\Middleware\Validation\Product;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\ListContent;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        $validators = [
            'id' => ['required', 'integer', new Exist('products', 'id')],
            'name' => ['string', 'max:255', new Unique('products', 'name')],
            'description' => ['string', 'max:250'],
            'price' => ['numeric', 'min:0'],
            'discount_type' => ['integer', 'in:0,1,2'],
            'discount_quantity' => ['numeric', 'min:0', 'required_with:discount_type'],

            'stock' => ['numeric', 'min:1'],

            'image' => ['array',new ListContent('image')],

            'weight' => ['string', 'min:0'],
            'height' => ['string', 'min:0'],
            'width' => ['string', 'min:0'],
            'length' => ['string', 'min:0'],

            'state' => ['integer', 'between:0,10'],
            'product_category_id' => ['integer', new Exist('products_categories', 'id')],
        ];
        if ($check_permission_admin) {
            $validators['user_id'] = ['integer', new Exist('users', 'id')];
        }else{
            $user_id = $session->user_id;
            $body['user_id'] = $user_id;
            $validators['id'] = [new Exist(
                table: 'products',
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