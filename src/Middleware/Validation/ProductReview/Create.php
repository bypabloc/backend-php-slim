<?php

namespace App\Middleware\Validation\ProductReview;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Model\Product;

use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\ListContent;

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
            'product_id' => ['required_without:parent_id','integer', new Exist('products', 'id')],
            'parent_id' => ['integer', new Exist('products_reviews', 'id')],
            'content' => ['required','string', 'max:250'], 
            'rating' => ['required_without:parent_id', 'integer','between:1,5'],
            'user_id' => ['integer', new Exist('users', 'id')],
            'image' => ['array',new ListContent('image')],
        ];
        if (!$check_permission_admin) {
            $body['user_id'] = $session->user_id;
            $validators['user_id'] = ['integer'];
        }else{
            array_push($validators['user_id'],'required');
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