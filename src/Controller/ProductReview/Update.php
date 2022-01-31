<?php

namespace App\Controller\ProductReview;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductReview;
use App\Model\Image;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product_review = ProductReview::find($body['id']);
        
        if(!empty($body['parent_id'])){
            $product_review->parent_id = $body['parent_id'];
        }else{
            $product_review->product_id =  $body['product_id'];
            $product_review->rating = $body['rating'];
        }
        
        $product_review->content = $body['content'];
        $product_review->user_id =  $session->user_id;
        $product_review->save();

        $id_product_review=$body['id'];

        if(isset($body['image'])){
            
            $image_model= new Image();

            $query_images = Image::where('table_id', $id_product_review)->where('table_name','products_reviews');
            $get_images = $query_images->get();

            foreach($get_images as $image){
                $image_model->deleteFile($image->path);            
            }    
            
            $query_images->delete();

            $images = [];

            $current_time = time();
            $current_time_format= date('Y-m-d h:i:s', $current_time);

            foreach ($body['image'] as $image) {
                $image_model->creatingImageProductsReview($image);
                array_push($images,[
                    "path" => $image_model->path,
                    "table_id" =>$id_product_review,
                    "table_name" => 'products_reviews',
                    "created_at"=>$current_time_format,
                    "updated_at"=>$current_time_format,
                ]);
                
            }
            
            Image::insert($images);
        }

        $product_review = ProductReview::where('id',$body['id'])->with('children')->with('images')->get();

        $res = [
            'data' => [
                'product_review' => $product_review,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}