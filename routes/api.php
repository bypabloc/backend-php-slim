<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Middleware\Auth;
use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    Route::prefix('auth')->group(function () {

        Route::middleware([
            Requests\AuthValidation\SignUp::class,
        ])
        ->post('sign_up', Controllers\AuthController\SignUp::class);

        Route::middleware([
            Requests\AuthValidation\SignIn::class,
        ])
        ->post('sign_in', Controllers\AuthController\SignIn::class);

        Route::middleware([
            Auth::class,
        ])
        ->post('sign_out', Controllers\AuthController\SignOut::class);

    });

    Route::middleware([
        Validations\Requests\Pagination::class,
        Requests\ProductCategoryValidation\GetAll::class
    ])
    ->get('products_categories', Controllers\ProductCategory\GetAllList::class);

    Route::middleware([
        Auth::class,
    ])->group(function () {
        Route::prefix('roles')->group(function () {

            Route::middleware([
                Validations\Requests\Pagination::class,
                Requests\RoleValidation\GetAll::class,
            ])
            ->get('get_all', Controllers\RoleController\GetAll::class);

            Route::middleware([
                Requests\RoleValidation\FindOne::class,
            ])
            ->get('find_one', Controllers\RoleController\FindOne::class);

            Route::middleware([
                Requests\RoleValidation\Create::class,
            ])
            ->post('create', Controllers\RoleController\Create::class);

            Route::middleware([
                Requests\RoleValidation\Update::class,
            ])
            ->post('update', Controllers\RoleController\Update::class);

        });

        Route::prefix('products_categories')->group(function () {

            Route::middleware([
                Validations\Requests\Pagination::class,
                Requests\ProductCategoryValidation\GetAll::class
            ])
            ->get('get_all',  Controllers\ProductCategory\GetAll::class);

            Route::middleware([
                Requests\ProductCategoryValidation\Find::class
            ])
            ->get('find',  Controllers\ProductCategory\Find::class);

            Route::middleware([
                Requests\ProductCategoryValidation\Create::class
            ])
            ->post('create', Controllers\ProductCategory\Create::class);

            Route::middleware([
                Requests\ProductCategoryValidation\Update::class
            ])
            ->post('update', Controllers\ProductCategory\Update::class);

            Route::middleware([
                Requests\ProductCategoryValidation\State::class
            ])
            ->post('state', Controllers\ProductCategory\State::class);

        });

    });
});
