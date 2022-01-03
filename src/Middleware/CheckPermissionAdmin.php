<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use App\Serializer\JsonResponse;

class CheckPermissionAdmin
{
    use JsonResponse;

    public $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $request = $request->withAttribute('check_permission_admin', $request->getAttribute('session')->user->role->can($this->permission));

        return $handler->handle($request);
    }
}