<?php

namespace App\Middleware\Validation\Cart;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

use App\Services\Validator;

use App\Model\Product;
use App\Model\CartProduct;

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
                            $products_del[$key] = $product;
                            $products_del[$key] = [
                                'cart_product_id' => $product['cart_product_id'],
                                'observation' => $product['observation'],
                                'state' => 0,
                            ];
                        }else{
                            $products_upd[$key] = [
                                'cart_product_id' => $product['cart_product_id'],
                                'observation' => $product['observation'],
                                'qty' => $product['qty'],
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

                        $product['id'] = $product['product_id'];
                        unset($product['product_id']);

                        $products_new[$key] = $product;
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

                $products_qty_uniqued = [];
                $errors = [];
                if ($products_new !== []) {
                    $db_products = Product::whereIn('id',$products_ids)
                        ->where('user_id',$validator->data['user_id'])
                        ->select('id','stock','price')
                        ->get()
                        ->toArray();

                    $db_products_object = [];
                    foreach ($db_products as $key => $product) {
                        $db_products_object[$product['id']] = [
                            'stock' => $product['stock'],
                            'price' => $product['price'],
                        ];
                    }
                    
                    foreach ($products_new as $key => $product) {
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

                    foreach ($products_new as $key => $product) {
                        $item_from_db = $db_products_object[$product['id']];
                        if(!isset($products_qty_uniqued[$product['id']])){
                            $products_qty_uniqued[$product['id']] = [
                                'qty' => (float) $product['qty'],
                                'sum' => (string) $product['qty'],
                                'stock' => $item_from_db['stock'],
                            ];
                        }else{
                            $products_qty_uniqued[$product['id']]['sum'] = $products_qty_uniqued[$product['id']]['sum'] . ' + ' . $product['qty'];
                            $products_qty_uniqued[$product['id']]['qty'] = $products_qty_uniqued[$product['id']]['qty'] + $product['qty'];
                        }
                    }
                }

                $products_upd_db = [];
                if ($products_upd !== []) {
                    $products_upd_db = CartProduct::whereIn('id',$carts_products_ids)
                        ->where('user_id',$validator->data['user_id'])
                        ->where('cart_id',$validator->data['id'])
                        ->with('product')
                        ->get()
                        ->toArray();

                    $db_update_products_object = [];
                    foreach ($products_upd_db as $key => $product) {
                        $db_update_products_object[$product['id']] = [
                            'stock' => $product['stock'],
                            'price' => $product['price'],
                            'product' => $product['product'],
                        ];
                    }

                    foreach ($products_upd as $key => $product) {
                        if(!isset($db_update_products_object[$product['cart_product_id']])){
                            $errors["products.".$key.".id"] = ["The id not found."];
                        }
                    }
                    if(!empty($errors)){
                        $response = new Response();
                        return $this->response($response, 422, [
                            'errors' => $errors,
                        ]);
                    }

                    foreach ($products_upd as $key => $product) {
                        $item_from_db = $db_update_products_object[$product['cart_product_id']];
                        if(!empty($product['qty'])){
                            $product_db = $item_from_db['product'];
                            if(!isset($products_qty_uniqued[$product_db['id']])){
                                $products_qty_uniqued[$product_db['id']] = [
                                    'qty' => (float) $product['qty'],
                                    'sum' => $product['qty'],
                                    // 'stock' => $item_from_db['stock'],
                                ];
                            }else{
                                $products_qty_uniqued[$product_db['id']]['sum'] = $products_qty_uniqued[$product_db['id']]['sum'] . ' + ' . $product['qty'];
                                $products_qty_uniqued[$product_db['id']]['qty'] = $products_qty_uniqued[$product_db['id']]['qty'] + $product['qty'];
                            }
                        }
                    }
                }
                if(!empty($errors)){
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }

                if ($products_new !== []) {
                    foreach ($products_new as $key => $product) {
                        $item_from_db = $db_products_object[$product['id']];
                        if($product['qty'] > $item_from_db['stock']){
                            $errors["products.".$key.".qty"] = ["The indicated quantity exceeds the quantity in stock", "The quantity in stock is: ". $item_from_db['stock']];
                        }else{
                            $products_new[$key]['stock'] = $item_from_db['stock'];
                            $products_new[$key]['price'] = $item_from_db['price'];
                        }
                    }
                }
                if ($products_upd !== []) {
                    foreach ($products_upd as $key => $product) {
                        if(!empty($product['qty'])){
                            $item_from_db = $db_update_products_object[$product['cart_product_id']];
                            $item_from_db = $item_from_db['product'];
                            if($product['qty'] > $item_from_db['stock']){
                                $errors["products.".$key.".qty"] = ["The indicated quantity exceeds the quantity in stock", "The quantity in stock is: ". $item_from_db['stock']];
                            }else{
                                $products_upd[$key]['stock'] = $item_from_db['stock'];
                                $products_upd[$key]['price'] = $item_from_db['price'];
                            }
                        }
                    }
                }
                if(!empty($errors)){
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }

                if ($products_new !== []) {
                    foreach ($products_new as $key => $product) {
                        $item_from_db = $db_products_object[$product['id']];
                        if($item_from_db['qty'] > $item_from_db['stock']){
                            $errors["products.".$key.".qty"] = [
                                "The indicated quantity exceeds the quantity in stock", 
                                "The amounts indicated were: " . $item_from_db['sum'], 
                                "The sum is: " . $item_from_db['qty'], 
                                "The quantity in stock is: ". $item_from_db['stock'],
                            ];
                        }
                    }
                }
                if ($products_upd !== []) {
                    foreach ($products_upd as $key => $product) {
                        if(!empty($product['qty'])){
                            $item_from_db = $db_update_products_object[$product['cart_product_id']];
                            $item_from_db = $item_from_db['product'];
                            if($item_from_db['qty'] > $item_from_db['stock']){
                                $errors["products.".$key.".qty"] = [
                                    "The indicated quantity exceeds the quantity in stock", 
                                    "The amounts indicated were: " . $item_from_db['sum'], 
                                    "The sum is: " . $item_from_db['qty'], 
                                    "The quantity in stock is: ". $item_from_db['stock'],
                                ];
                            }
                        }
                    }
                }
                if(!empty($errors)){
                    $response = new Response();
                    return $this->response($response, 422, [
                        'errors' => $errors,
                    ]);
                }

                $products_new_array = [];
                $products_upd_array = [];
                foreach ($products_new as $key => $value) {
                    unset($value['stock']);
                    array_push($products_new_array, $value);
                }
                foreach ($products_upd as $key => $value) {
                    unset($value['stock']);
                    array_push($products_upd_array, $value);
                }
                foreach ($products_del as $key => $value) {
                    array_push($products_upd_array, $value);
                }

                $validator->data['products_new'] = $products_new_array;
                $validator->data['products_upd'] = $products_upd_array;
                unset($validator->data['products']);
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