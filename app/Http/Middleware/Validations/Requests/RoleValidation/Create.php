<?php

namespace App\Http\Middleware\Validations\Requests\RoleValidation;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Services\Response;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
        ]);

        if($validator->fails()){
            return Response::UNPROCESSABLE_ENTITY(
                message: 'Validation failed.',
                errors: $validator->errors(),
            );
        }

        $request->merge([
            'body' => $validator->validated(),
        ]);

        return $next($request);
    }
}
