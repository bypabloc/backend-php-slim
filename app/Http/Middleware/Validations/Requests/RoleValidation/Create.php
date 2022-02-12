<?php

namespace App\Http\Middleware\Validations\Requests\RoleValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Middleware\ListContent;
use App\Http\Middleware\ExistList;
use App\Http\Middleware\ListNotRepeat;

use App\Services\Response;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['array',
                                Rule::when(is_array($request->permissions), [
                                    new ListContent('integer'),
                                    new ExistList('permissions', 'id'),
                                    new ListNotRepeat()
                                ])
                            ],
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
