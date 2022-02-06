<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

use App\Http\Controllers\ProductCategory;
use App\Http\Middleware\Validations\Requests\ProductCategoryValidation;

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

        /*
        * Consulta todas las categorias de productos
        */
    Route::middleware([Validations\Requests\Pagination::class,ProductCategoryValidation\GetAll::class])->get('products_categories', ProductCategory\GetAllList::class);

    Route::prefix('products_categories')->group(function () {

        // Route::middleware([Validations\Requests\Pagination::class,ProductCategoryValidation\GetAll::class])->get('get_all', ProductCategory\GetAll::class);

        // $app->get('/find', ProductCategory\Find::class)->add(new \App\Middleware\Validation\ProductCategory\Find())->add(new CheckPermissionAdmin('products_categories.find.admin'));
        Route::middleware([ProductCategoryValidation\Create::class])->post('create', ProductCategory\Create::class);
        // $app->post('/create', ProductCategory\Create::class)->add(new \App\Middleware\Validation\ProductCategory\Create())->add(new CheckPermissionAdmin('products_categories.create.admin'));
        // $app->post('/update', ProductCategory\Update::class)->add(new \App\Middleware\Validation\ProductCategory\Update())->add(new CheckPermissionAdmin('products_categories.update.admin'));
        // $app->post('/state', ProductCategory\State::class)->add(new \App\Middleware\Validation\ProductCategory\State())->add(new CheckPermissionAdmin('products_categories.state.admin'));

    });

        /*
        * Consulta una categoria de productos por su slug
        */
        // $app->get('/product_category/{slug}', ProductCategory\GetBySlug::class)->add(new \App\Middleware\Validation\ProductCategory\GetBySlug());


});
