<?php

declare(strict_types=1);

namespace App\Serializer;

trait RequestValidatorErrors
{
    public function getErrors(
        array $data,
    ): object {
        $errors = [];
        foreach ($data as $error) {
            if(isset($errors[$error['property']])){
                array_push($errors[$error['property']], $error['message']);
            }else{
                $errors[$error['property']] = [$error['message']];
            }
        }
        return (object) $errors;
    }
}