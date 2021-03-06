<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

class CanPermission
{
    use JsonResponse;

    public $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try{
            $permission = $this->permission;
            
            $session = $request->getAttribute('session');

            $canPermission = $session->user()->role->can($this->permission);

            if(!$canPermission){
                $response = new Response();
                return $this->response($response, 401, [
                    'errors' => ['Permission denied'],
                ]);
            }

            return $handler->handle($request);

        } catch (\Throwable $th) {
            $response = new Response();
            $response = $this->response($response, 500, [
                'message' => $th->getMessage(),
                'getFile' => $th->getFile(),
                'getLine' => $th->getLine(),
            ]);
            return $response;
        }
    }
}