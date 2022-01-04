<?php

namespace App\Middleware\Validation\Cart;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Model\Product;

use App\Middleware\Validation\Rule\Unique;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\OnlyLetters;
use App\Middleware\Validation\Rule\IsBase64;
use App\Middleware\Validation\Rule\ListContent;
use App\Middleware\Validation\Rule\ListNotRepeat;
use App\Middleware\Validation\Rule\RegisterActive;
use App\Middleware\Validation\Rule\ArrayOfObjects;

class Create
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');
        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');

        $validators = [
            'user_id' => ['integer', new Exist('users', 'id'),],
            'observation' => ['string', 'max:250'],
            'address' => ['string', 'max:250'],
            'state' => ['integer', 'between:0,10'],
            'products' => [
                'array', 
            ],
            'products.*.id' => ['required', 'integer'],
            'products.*.qty' => ['required', 'numeric'],
        ];
        if (!$check_permission_admin) {
            $body['user_id'] = $session->user_id;
            $validators['user_id'] = ['integer'];
        }else{
            array_push($validators['user_id'],'required');
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
                
                $products_ids = [];
                foreach ($products as $key => $product) {
                    array_push($products_ids, $product['id']);
                }
                $db_products = Product::whereIn('id',$products_ids)->where('user_id',$validator->data['user_id'])->select('id','stock')
                    ->get()
                    ->toArray();
                
                $db_products_object = [];
                foreach ($db_products as $key => $product) {
                    $db_products_object[$product['id']] = $product['stock'];
                }

                $errors = [];
                foreach ($products as $key => $product) {
                    if(!isset($db_products_object[$product['id']])){
                        $errors["products.".$key.".id"] = ["The id not found."];
                    }
                }
                if(!empty($errors)){
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }

                foreach ($products as $key => $product) {
                    $item_from_db = $db_products_object[$product['id']];
                    if($product['qty'] > $item_from_db){
                        $errors["products.".$key.".qty"] = ["The indicated quantity exceeds the quantity in stock", "The quantity in stock is: $item_from_db"];
                    }
                }
                if(!empty($errors)){
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }
                
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