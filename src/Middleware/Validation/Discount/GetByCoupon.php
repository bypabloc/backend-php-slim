<?php

namespace App\Middleware\Validation\Discount;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

use App\Middleware\Validation\Rule;

use App\Model\Discount;

class GetByCoupon
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $args = $request->getAttribute('args');

        $validators = [
            'coupon' => ['required', 'string', 'between:5,15'],
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

            $discount = Discount::where('coupon', $validator->data['coupon'])->where('is_active', 1)->first();

            if(!$discount){
                $response = new Response();
                return $this->response($response, 404, [
                    'errors' => [
                        'discount' => 'Discount not found',
                    ],
                ]);
            }

            $validator->data['discount'] = $discount;

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