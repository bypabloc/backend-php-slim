<?php

namespace App\Middleware\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

class RegisterActive implements Rule, DataAwareRule
{
    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $table,
        protected $column = 'state',
        protected $state = 1,
        protected $column_owner = 'user_id',
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
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }

        $value = intval($value);

        $query = Capsule::table($this->table)->where([
            $this->column => $this->state,
            $this->column_owner => $value,
        ]);
        if(isset($this->data['id'])){
            $query->where('id', '!=', $this->data['id']);
        }

        return !$query->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Active records were found, they need to be finalized in order to continue with this request.';
    }
}