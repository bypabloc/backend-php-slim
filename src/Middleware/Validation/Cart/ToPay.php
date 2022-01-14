<?php

namespace App\Middleware\Validation\Cart;

use Psr\Http\Message\ServerRequestInterface as RequestClass;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Model\Cart;
use App\Model\Product;
use App\Model\CartProduct;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;
use App\Middleware\Validation\Rule\RegisterState;

class ToPay
{
    use JsonResponse;
    
    public function __invoke(RequestClass $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        // $cart = Cart::find($body['id']);
        // $cart->state = 1;
        // $cart->save();

        $validators = [
            'id' => ['required', 'integer', new Exist('carts', 'id')],
            'user_id' => ['integer', new Exist('users', 'id'),],
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

            $cart_id = $validator->data['id'];
            $cart = Cart::where('id',$cart_id)->with('products')->first();
            if($cart['products']){
                $products = $cart['products'];
                
                $errors = [];
                $products_object = [];
                foreach ($products as $key => $product) {
                    if(isset($products_object[$product['id']])){
                        $products_object[$product['id']]['qty'] += $product['pivot']['qty'];
                    }else{
                        $products_object[$product['id']] = [
                            'key' => $key,
                            'qty' => $product['pivot']['qty'],
                            'stock' => $product['stock'],
                            'name' => $product['name'],
                        ];
                    }
                }
                foreach ($products_object as $key => $product) {
                    if($product['qty'] > $product['stock']){
                        $errors["products.".$key.".qty"] = ['The product ' . $product['name'] . ' does not have enough stock.'];
                    }
                }
            }

            if(!empty($errors)){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $errors,
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