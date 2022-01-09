<?php

namespace App\Middleware\Validation\Cart;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Model\CartProduct;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;

class CartProductDelivered
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');

        // $cart_product = CartProduct::find($body['cart_product_id']);
        // $cart_product->state = 2;
        // $cart_product->save();

        $validators = [
            'cart_product_id' => ['required', 'integer', new Exist('carts_products', 'id')],
        ];

        try {
            $validator = new Validator();

            $validator->validate($body, $validators);

            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }

            $check_permission_admin = $request->getAttribute('check_permission_admin');

            $cart_product = CartProduct::find($validator->data['cart_product_id']);
            $cart_product->product;

            if (!$check_permission_admin) {
                if ($cart_product['user_id'] != $session->user_id) {
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => [
                            'cart_product_id' => ['The cart product id not found.'],
                        ],
                    ]);
                }
            }

            if ($cart_product['state'] != 3) {
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => [
                        'cart_product_id' => [
                            'State product must be: ' . CartProduct::STATES[3],
                            'State current is: ' . CartProduct::STATES[$cart_product['state']],
                        ],
                    ],
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