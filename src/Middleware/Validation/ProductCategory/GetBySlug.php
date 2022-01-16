<?php

namespace App\Middleware\Validation\ProductCategory;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule;

use App\Model\ProductCategory;

class GetBySlug
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $args = $request->getAttribute('args');

        $validators = [
            'slug' => [
                'required', 
                'string',
            ],
        ];

        try {
            $validator = new Validator();

            $validator->validate($args, $validators);

            if(!$validator->isValid()){
                $response = new Response();
                return $this->response($response, 422, [
                    'errors' => $validator->errors,
                ]);
            }

            $product_category = ProductCategory::where('slug', $validator->data['slug'])->where('is_active', 1)->first();

            if(!$product_category){
                $response = new Response();
                return $this->response($response, 404, [
                    'errors' => [
                        'product_category' => 'Product not found',
                    ],
                ]);
            }

            $validator->data['product_category'] = $product_category;

            $request = $request->withAttribute('args', $validator->data);
            
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