<?php

namespace App\Middleware\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

class ExistList implements Rule, DataAwareRule
{
    public $table = '';
    public $column = '';

    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
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
        $ids = Capsule::table($this->table)->whereIn($this->column, $value)->pluck('id')->toArray();
        foreach($value as $key => $v){
            if(in_array($v, $ids)){
                unset($value[$key]);
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
        return 'The :attribute not found all items, the items are: ' . implode(', ', $this->data['value'] );
    }
}