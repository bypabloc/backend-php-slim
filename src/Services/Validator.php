<?php

namespace App\Services;

use Illuminate\Validation;
use Illuminate\Filesystem;
use Illuminate\Translation;

class Validator
{
    public $data = null;
    public $errors = null;
    public $isValid = false;

    function __construct()
    {
    }

    public function validate(array $params, array $validators): void
    {
        $filesystem = new Filesystem\Filesystem();
        $fileLoader = new Translation\FileLoader($filesystem, '');
        $translator = new Translation\Translator($fileLoader, 'en_US');
        $factory = new Validation\Factory($translator);

        $validator = $factory->make($params, $validators, [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'integer' => 'The :attribute must be a integer.',
            'between' => 'The :attribute must have between :min and :max',
            'array' => 'The :attribute must be a list (array).',
            'email' => 'The :attribute field must type email.',
            'min' => 'The :attribute field must greater than :min.',
            'max' => 'The :attribute field must less than :max.',
            'same' => 'The :attribute field must same :size.',
            'in' => 'The :attribute must be one of the following types: :values',
            'file.image' => 'The :attribute must be an image.',
            'file.max' => 'The :attribute must be less than :max kilobytes.',
            'before' => 'The :attribute must be less than today.',
            'after' => 'The :attribute must be after than today.',
            'date'=> 'The :attribute must be a valid date (Y-m-d).',
            'regex'=> 'The :attribute must be a format valid.',
            'required_without'=> 'The :attribute field is required',
            'required_without_all'=> 'The :attribute field is required',
        ]);

        if(!$validator->fails()){
            $this->data = $validator->validated();
            $this->isValid = true;
        }else{
            $this->errors = $validator->errors();
            $this->isValid = false;
        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}