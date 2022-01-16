<?php

namespace App\Controller\ProductReview;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductReview;
use App\Model\Image;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product_review = new ProductReview();

        $product_review->product_id =  $body['product_id'];
        
        if(!empty($body['parent_id'])){
            $product_review->parent_id = $body['parent_id'];
        }
        
        $product_review->content = $body['content'];
        $product_review->rating = $body['rating'];
        $product_review->user_id =  $session->user_id;

        // $product_review->creatingCustom();

        $product_review->save();
        
        if(isset($body['image'])){
            
            $image_model= new Image();
            $images = [];
            foreach ($body['image'] as $image) {
                $image_model->creatingImage($image);
                array_push($images,[
                    "path" => $image_model->path,
                    "table_id" =>$product_review->id,
                    "table_name" => 'products_reviews',
                    // "table_name" => $product_review->table,
                ]);
                
            }
            
            Image::insert($images);
        }
        

        $res = [
            'data' => [
                'product_review' => $product_review,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}