<?php

namespace App\Middleware\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

class UniqueWhitSugestion implements Rule, DataAwareRule
{
    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $table,
        protected $column = 'id',
        protected $id = null,
        protected $owner = null,
    )
    {
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->id) {
            $this->data['id'] = $this->id;
        }

        $query = Capsule::table($this->table)->where([
            $this->column => $value,
        ]);

        if(isset($this->data['id'])){
            $query->where('id', '!=', $this->data['id']);
        }

        if ($this->owner) {
            if(isset($this->data[$this->owner]) && !empty($this->data[$this->owner])){
                $query->where($this->owner, $this->data[$this->owner]);
            }
        }


        
        $row = $query->first();

        return $row === null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $coupon = $this->data[$this->column];

        $size_coupon = 15;
        $length_coupon = strlen($coupon);

        $coupon_split = explode('-', $coupon);
        if(sizeof($coupon_split)!=1){
            if($length_coupon==$size_coupon){
                $coupon = $coupon_split[1];
                $length_coupon = strlen($coupon_split[1]);
                print_r($coupon_split[1]);
            }
        }
        
        $length = $size_coupon-$length_coupon-1;
        if($size_coupon==$length_coupon){
            $length=1;
        }
        
        print_r($size_coupon."\n".$length_coupon);
        $length_sugestion = str_repeat(9, $length);
        $this->data[$this->column] = rand(1, intval($length_sugestion)) . "-" . $coupon;
        
        return 'The :attribute already exists.  '. $this->data[$this->column];
    }
}