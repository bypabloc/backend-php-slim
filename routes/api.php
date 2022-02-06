<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    Route::prefix('auth')->group(function () {

        Route::middleware([
            Requests\AuthValidation\SignIn::class,
        ])
        ->get('sign_in', Controllers\AuthController\SignIn::class);

    });

    Route::prefix('roles')->group(function () {
        
        Route::middleware([
            Validations\Requests\Pagination::class,
            Requests\RoleValidation\GetAll::class,
        ])
        ->get('get_all', Controllers\RoleController\GetAll::class);
        
        Route::middleware([
            Requests\RoleValidation\Create::class,
        ])
        ->post('create', Controllers\RoleController\Create::class);
        
        Route::middleware([
            Requests\RoleValidation\Update::class,
        ])
        ->post('update', Controllers\RoleController\Update::class);
        
    });

});