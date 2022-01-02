<?php

namespace App\Middleware\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

class ListContent implements Rule, DataAwareRule
{
    public $type = '';

    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        if (!is_array($value)) {
            return true;
        }
        if (empty($value)) {
            return true;
        }
        
        foreach($value as $key => $v){
            switch ($this->type) {
                case 'integer':
                    if (filter_var($v, FILTER_VALIDATE_INT)) {
                        unset($value[$key]);
                    }
                    break;
                
                default:
                    if (filter_var($v, FILTER_VALIDATE_INT)) {
                        unset($value[$key]);
                    }
                    break;
            }
        }

        $this->data['value'] = $value;

        return $value === [];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be list of ' . $this->type . ', the items with errors are: ' . implode(', ', $this->data['value'] );
    }
}