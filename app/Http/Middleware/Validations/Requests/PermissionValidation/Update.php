<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Update
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
            'id' => ['required', 'integer','exists:permissions,id'],
            'name' => [
                'string','min:5','max:20',
                !empty($request->id) ? 'unique:permissions,name,'.$request->id :null
            ],
            'alias' => [
                'required_with:name','string','min:5','max:20',
                !empty($request->id) ? 'unique:permissions,alias,'.$request->id :null
            ],
            'is_active' => ['boolean']
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
