<?php

namespace App\Middleware\Validation\Cart;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Model\Product;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;
use App\Middleware\Validation\Rule\RegisterActive;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        $validators = [
            'id' => ['required', 'integer', new Exist('carts', 'id')],
            'user_id' => ['integer', new Exist('users', 'id'),],
            'observation' => ['string', 'max:250'],
            'address' => ['string', 'max:250'],
            'state' => ['integer', 'between:0,10'],
            'products' => [ 'array' ],
            'products.*.product_id' => ['integer'],
            'products.*.cart_product_id' => ['integer'],
            'products.*.qty' => ['numeric'],
            'products.*.observation' => ['string', 'max:250'],
            'products.*.state' => ['integer', 'between:0,10'],
        ];
        if ($check_permission_admin) {
            $validators['user_id'] = ['integer', new Exist('users', 'id')];
        }else{
            $user_id = $session->user_id;
            $body['user_id'] = $user_id;
            $validators['id'] = [new Exist(
                table: 'carts',
                column: 'id',
                owner: 'user_id',
            )];
        }
        array_push(
            $validators['user_id'], 
            new RegisterActive(
                table: 'carts',
                column: 'state',
                state: 1,
            )
        );

        try {
            $validator = new Validator();

            $validator->validate($body, $validators);

            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }

            if($products = $validator->data['products']){
                
                $products_news = [];
                $products_update = [];
                $products_delete = [];
                foreach ($products as $key => $product) {
                    if(!isset($product['cart_product_id']) || empty($product['cart_product_id'])){
                        array_push($products_news, $product);
                    }elseif (isset($product['product_id'])) {
                        if(isset($product['state']) && $product['state'] === 0){
                            array_push($products_delete, $product);
                        }else{
                            array_push($products_update, $product);
                        }
                    }
                }

                $errors = [];
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $errors,
                    'products_update' => $products_update,
                    'products_news' => $products_news,
                    'products_delete' => $products_delete,
                ]);

                $validator->data['products'] = $products;
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