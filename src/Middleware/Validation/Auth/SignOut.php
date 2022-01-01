<?php

namespace App\Middleware\Validation\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

use App\Services\Validator;

class SignOut
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $bodyPrev = (array) $request->getParsedBody() ?: [];

        $array = array_merge($bodyPrev, [
            'prueba' => 'prueba',
        ]);

        $request = $request->withParsedBody($array);
        return $handler->handle($request);
    }
}