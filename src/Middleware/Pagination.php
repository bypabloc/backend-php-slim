<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use Illuminate\Validation;
use Illuminate\Filesystem;
use Illuminate\Translation;

use App\Serializer\JsonResponse;
use App\Serializer\RequestValidatorErrors;

class Pagination
{
    use RequestValidatorErrors;
    use JsonResponse;
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = (array) $request->getQueryParams(); // get all params get query params
        // $params = (array) $request->getParsedBody(); // get all body params

        $filesystem = new Filesystem\Filesystem();
        $fileLoader = new Translation\FileLoader($filesystem, '');
        $translator = new Translation\Translator($fileLoader, 'en_US');
        $factory = new Validation\Factory($translator);

        $validator = $factory->make($params, [
            'page' => ['required', 'integer', 'min:1'],
            'per_page' => ['required', 'integer', 'min:5'],
        ], [
            'required' => 'The :attribute field is required.',
        ]);

        if(!$validator->fails()){
            $request = $request->withParsedBody([
                'data' => $validator->validated(),
            ]);
            $response = $handler->handle($request);
        }else{
            $response = new Response();
            $response = $this->response($response, 422, [
                'errors' => $validator->errors(),
            ]);
        }


        // if ($validator->isValid()) {
        //     $response = $responseOld;
        // } else {
        //     $response = new Response();
        //     $response = $response->withStatus(400);
        // }
        
        // if(1==1){
        //     $response = new Response();

        //     $responseOld->getBody()->write(json_encode([
        //         'params' => $params,
        //     ]));
        // }else{
        //     $response = $responseOld;
        // }
    
        return $response;
    }
}