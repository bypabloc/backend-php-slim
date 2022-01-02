<?php

namespace App\Middleware\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

class Exist implements Rule, DataAwareRule
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
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }

        $value = intval($value);

        return Capsule::table($this->table)->where([
            $this->column => $value,
        ])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute not found.';
    }
}