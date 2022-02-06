<?php

namespace App\Http\Middleware\Validations\Requests\RoleValidation;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Role;

class GetAll
{
    public function handle(Request $request, Closure $next)
    {
        $role = new Role();

        $validator = Validator::make($request['query'], [
            'sort_by' => ['nullable', 'string', 'in:' . implode(',', $role->getFillable())],
            'sort_direction' => ['nullable', 'string', 'in:asc,desc'],
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = $request['query'];

        $query['page'] = $query['page'] ?? 1;
        $query['per_page'] = $query['per_page'] ?? 10;

        $request->merge([
            'query' => $query,
        ]);
        
        return $next($request);
    }
}