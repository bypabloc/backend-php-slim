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
use App\Middleware\Validation\Rule\RegisterState;

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
            new RegisterState(
                table: 'carts',
                column: 'state',
                state: 1,
                equals: false,
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
                
                $products_ids = [];
                $carts_products_ids = [];
                $carts_products_counts = [];

                $products_new = [];
                $products_upd = [];
                $products_del = [];

                foreach ($products as $key => $product) {
                    if(isset($product['cart_product_id']) || !empty($product['cart_product_id'])){
                        array_push($carts_products_ids, $product['cart_product_id']);
                        if(isset($product['state']) && $product['state'] === 0){
                            $products_del[$product['cart_product_id']] = [
                                'key' => $key,
                                'state' => 0,
                            ];
                        }else{
                            $products_upd[$product['cart_product_id']] = [
                                'key' => $key,
                                'product' => $product,
                            ];
                        }
                        if(isset($carts_products_counts[$product['cart_product_id']])){
                            $carts_products_counts[$product['cart_product_id']] = [
                                'keys' => [ ...$carts_products_counts[$product['cart_product_id']]['keys'], $key ],
                                'count' => $carts_products_counts[$product['cart_product_id']]['count'] + 1,
                            ];
                        }else{
                            $carts_products_counts[$product['cart_product_id']] = [
                                'keys' => [ $key ],
                                'count' => $carts_products_counts[$product['cart_product_id']] + 1,
                            ];
                        }
                    }elseif (isset($product['product_id'])) {
                        array_push($products_ids, $product['product_id']);
                        $products_new[$product['product_id']] = [
                            'key' => $key,
                            'product' => $product,
                        ];
                    }
                }

                
                if( count($carts_products_ids) !== count(array_unique($carts_products_ids)) ){

                    $errors = [];

                    foreach ($carts_products_counts as $count_data) {
                        if ($count_data['count'] > 1) {
                            foreach ($count_data['keys'] as $key) {
                                $errors["products.".$key.".cart_product_id"] = ["This item is repeated"];
                            }
                        }
                    }

                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }

                if ($products_new !== []) {
                    
                }

                $errors = [];
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $errors,
                    'products_ids' => $products_ids,
                    'carts_products_ids' => $carts_products_ids,
                    'products_upd' => $products_upd,
                    'products_new' => $products_new,
                    'products_del' => $products_del,
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