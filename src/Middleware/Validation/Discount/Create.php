<?php

namespace App\Middleware\Validation\Discount;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule\UniqueWhitSugestion;
use App\Middleware\Validation\Rule\Exist;
use App\Middleware\Validation\Rule\ExistList;
use App\Middleware\Validation\Rule\ListNotRepeat;
use App\Middleware\Validation\Rule\ListContent;

class Create
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getAttribute('body');

        if(isset($body['coupon'])){
            $body['coupon'] = strtoupper($body['coupon']);
        }

        $session = $request->getAttribute('session');
        $check_permission_admin = $request->getAttribute('check_permission_admin');
        
        $validators = [
            'coupon' => ['required', 'string', 'between:5,15' ,new UniqueWhitSugestion('discounts', 'coupon')],
            'discount_type' => ['required','integer', 'in:0,1,2'],
            'discount_quantity' => ['required', 'numeric', 'min:0', 'required_with:discount_type'],
            'mount_max_discount' => ['required','numeric'],  // ,'between:0,100' validation
            'is_active' => ['boolean'],
            'created_by' => ['required','integer', new Exist('users', 'id')],
            'expired_at'=>['required','date','after:today'],

            'user_id'=>['required_without_all:product_id,category_id', 'array',new ListContent('integer'), new ExistList('users', 'id'), new ListNotRepeat()],
            'product_id'=>['required_without_all:user_id,category_id', 'array',new ListContent('integer'), new ExistList('products', 'id'), new ListNotRepeat()],
            'category_id'=>['required_without_all:user_id,product_id', 'array',new ListContent('integer'), new ExistList('products_categories', 'id'), new ListNotRepeat()],
        ];
        if (!$check_permission_admin) {
            $body['created_by'] = $session->user_id;
            $validators['created_by'] = ['integer'];
        }else{
            array_push($validators['created_by'],'required');
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
                'errors' => [
                    'message' => $th->getMessage(),
                    'getFile' => $th->getFile(),
                    'getLine' => $th->getLine(),
                ],
            ]);
        }

        return $response;
    }
}