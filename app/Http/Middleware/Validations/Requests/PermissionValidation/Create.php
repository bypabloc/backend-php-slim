<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        if(isset($request['name'])){
            $request['name'] = strtolower($request['name']);
        }
        if(isset($request['alias'])){
            $request['alias'] = strtolower($request['alias']);
        }
        $lowerCaseToArray = array(
            'alias' => $request['alias'],
            'name' => $request['name'],
        );
        $validate = array_merge($request['body'], $lowerCaseToArray);

        $validator = Validator::make($validate, [
            'name' => ['required','string','min:5','max:20','unique:permissions'],
            'alias' => ['required','string','min:5','max:20','unique:permissions'],
            'is_active' => ['boolean'],
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $request->merge([
            'body' => $validator->validated(),
        ]);

        return $next($request);
    }
}
