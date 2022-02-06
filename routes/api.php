<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Controllers\SessionController;

use App\Http\Middleware\Validations;

use App\Http\Controllers\RoleController;
use App\Http\Middleware\Validations\Requests\RoleValidation;

use App\Http\Controllers\ProductCategory;
use App\Http\Middleware\Validations\Requests\ProductCategoryValidation;

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    Route::prefix('roles')->group(function () {

        Route::middleware([Validations\Requests\Pagination::class,RoleValidation\GetAll::class])->get('get_all', RoleController\GetAll::class);

        Route::middleware([RoleValidation\Create::class])->post('create', RoleController\Create::class);



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
