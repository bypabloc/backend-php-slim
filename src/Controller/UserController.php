<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    public function list(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = array('name' => 'Rob', 'age' => 40);

        return $this->jsonResponse($response, 200, $data);
    }
}

// {
//     public function __construct()
//     {
//         var_dump('__METHOD__');
//     }
    
//     public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
//     {
//         // your code here
//         // use $this->view to render the HTML
//         // ...
        
//         return $response;
//     }
// }