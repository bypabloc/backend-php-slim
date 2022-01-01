<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class BodyParser
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getHeaders();
        $headers_list = [];
        foreach ($headers as $name => $values) {
            $headers_list[$name] = implode(", ", $values);
        }

        $bodyPrev = (array) $request->getParsedBody() ?: [];

        $params = (array) $request->getQueryParams() ?: [];

        $request = $request->withAttribute('body', $bodyPrev);
        $request = $request->withAttribute('params', $params);
        $request = $request->withAttribute('headers', $headers_list);

        return $handler->handle($request);
    }
}