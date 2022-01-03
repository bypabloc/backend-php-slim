<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Services\Slug;

class Product extends Model
{
    use Pagination;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'slug',

        'price',

        'discount_type',
        // 1 = percentage
        // 2 = amount
        'discount_quantity',

        'stock',

        'image',

        'weight',
        'height',
        'width',
        'length',

        'likes',

        'state',

        'user_id',

        'product_category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function creatingCustom()
    {
        $this->slug = Slug::make($this->name);

        if(isset($this->image)){
            $name_file = time() . bin2hex(random_bytes(50));
            $this->image = $this->save_base64_image($this->image, $name_file ,'product_images');
        }
    }

    public function updatingCustom()
    {
        $this->slug = Slug::make($this->name);
        if(isset($this->image)){
            $name_file = time() . bin2hex(random_bytes(50));
            $this->image = $this->save_base64_image($this->image, $name_file ,'product_images');
        }
    }

    public function save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash="" ) {
        //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }      
        //
        //data is like:    data:image/png;base64,asdfasdfasdf
        $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
        $mime=$splited[0];
        $data=$splited[1];
    
        $mime_split_without_base64=explode(';', $mime,2);
        $mime_split=explode('/', $mime_split_without_base64[0],2);
        if(count($mime_split)==2){
            $extension=$mime_split[1];
            //if($extension=='javascript')$extension='js';
            //if($extension=='text')$extension='txt';
            $output_file_with_extension=$output_file_without_extension.'.'.$extension;
        }
        if (!file_exists($path_with_end_slash)) {
            mkdir($path_with_end_slash, 0777, true);
        }
        file_put_contents( $path_with_end_slash . '/' . $output_file_with_extension, base64_decode($data) );
        return $path_with_end_slash . '/' . $output_file_with_extension;
    }
}