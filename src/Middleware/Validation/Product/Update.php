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
use App\Middleware\Validation\Rule\IsBase64;

class Update
{
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        try {
            $validator = new Validator();

            $validator->validate($body, [
                'id' => ['required', 'integer', new Exist('products', 'id')],
                'name' => ['string', 'max:255', new Unique('products', 'name')],
                'description' => ['string', 'max:250'],
                'price' => ['numeric', 'min:0'],
                'discount_type' => ['integer', 'in:0,1,2'],
                'discount_quantity' => ['numeric', 'min:0', 'required_with:discount_type'],

                'stock' => ['numeric', 'min:1'],

                'image' => [new IsBase64(
                    types: ['png','jpg', 'jpeg', 'gif'],
                    size: 2048
                ,)],

                'weight' => ['string', 'min:0'],
                'height' => ['string', 'min:0'],
                'width' => ['string', 'min:0'],
                'length' => ['string', 'min:0'],

                'state' => ['integer', 'between:0,10'],
            ], );
    
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