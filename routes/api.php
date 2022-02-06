<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Controllers\SessionController;

use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;

use App\Http\Controllers;

use App\Http\Controllers\RoleController;
use App\Http\Middleware\Validations\Requests\RoleValidation;

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    Route::prefix('auth')->group(function () {

        // Route::middleware([Requests\AuthValidation\SignIn::class])->get('sign_in', Controllers\AuthController\SignIn::class);

    });

    Route::prefix('roles')->group(function () {

        Route::middleware([Validations\Requests\Pagination::class,RoleValidation\GetAll::class])->get('get_all', RoleController\GetAll::class);
        Route::middleware([RoleValidation\Create::class])->post('create', RoleController\Create::class);

    });

});