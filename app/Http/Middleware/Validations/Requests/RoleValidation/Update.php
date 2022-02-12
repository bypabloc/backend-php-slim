<?php

namespace App\Http\Middleware\Validations\Requests\RoleValidation;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Middleware\ListContent;
use App\Http\Middleware\ExistList;
use App\Http\Middleware\ListNotRepeat;
use App\Services\Response;

class Update
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => [
                'required',
                'integer',
                'exists:roles,id'
            ],
            'name' => [
                'string',
                'max:255',
                !empty($request->id) ? 'unique:roles,name,'.$request->id :null
            ],
            'is_active' => [
                'boolean',
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
