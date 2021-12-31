<?php

namespace App\Services;

class Pagination
{
    function __construct()
    {
    }

    public function validate(array $params, array $validators, array $messages): void
    {
        $filesystem = new Filesystem\Filesystem();
        $fileLoader = new Translation\FileLoader($filesystem, '');
        $translator = new Translation\Translator($fileLoader, 'en_US');
        $factory = new Validation\Factory($translator);

        $validator = $factory->make($params, $validators, $messages);

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