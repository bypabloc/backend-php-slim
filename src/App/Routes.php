<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Slim\Exception\HttpNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controller\User;
use App\Controller\Role;
use App\Controller\Migration;
use App\Controller\Auth;
use App\Controller\Permission;
use App\Controller\ProductCategory;
use App\Controller\Product;
use App\Controller\ProductReview;
use App\Controller\MyProfile;
use App\Controller\Cart;
use App\Controller\Discount;
use App\Middleware\Token;
use App\Middleware\BodyParser;
use App\Middleware\CanPermission;
use App\Middleware\CheckPermissionAdmin;

use \App\Services\Logger;

try {
    $app->get('/test', function ($request, $response, array $args) {
        $response->getBody()->write('Prueba');
        
        return $response;
    });

    $app->group('/api/v1', function (RouteCollectorProxy $app) {

        $app->group('/auth', function (RouteCollectorProxy $app) {

            $app->post('/sign_up', Auth\SignUp::class)->add(new \App\Middleware\Validation\Auth\SignUp());
            $app->post('/sign_in', Auth\SignIn::class)->add(new \App\Middleware\Validation\Auth\SignIn());
            $app->post('/sign_out', Auth\SignOut::class)->add(Token::class);

        });

        /*
        * Consulta de todos los productos por usuario "/{nickname}"
        */
        $app->get('/user/{nickname}', User\GetByNickname::class)->add(new \App\Middleware\Validation\User\GetByNickname());

        /*
        * Consulta todos los usuarios que tengan productos activos para vender
        */
        $app->get('/users_all', User\GetAllList::class)->add(new \App\Middleware\Pagination());

        /*
        * Consulta todas las categorias de productos
        */
        $app->get('/products_categories', ProductCategory\GetAllList::class)->add(new \App\Middleware\Pagination());

        /*
        * Consulta una categoria de productos por su slug
        */
        $app->get('/product_category/{slug}', ProductCategory\GetBySlug::class)->add(new \App\Middleware\Validation\ProductCategory\GetBySlug());

        /*
        * Consulta un producto por su slug
        */
        $app->get('/product/{slug}', Product\GetBySlug::class)->add(new \App\Middleware\Validation\Product\GetBySlug());

        /*
        * Consulta todos los productos apartir del nombre indicado y que se asemeje a los slug 
        */
        $app->get('/products_search', Product\SearchBySlug::class)->add(new \App\Middleware\Validation\Product\SearchBySlug())->add(new \App\Middleware\Pagination());

        /*
        *  Consulta un cupon por codigo
        */
        // $app->get('/discount/{coupon}', Discount\GetByCoupon::class)->add(new \App\Middleware\Validation\Discount\GetByCoupon()); //falta programar

        $app->group('/carts', function (RouteCollectorProxy $app) {

            $app->get('/get_all', Cart\GetAll::class)->add(new \App\Middleware\Pagination())->add(new CheckPermissionAdmin('carts.get_all.admin'));
            $app->get('/me_active', Cart\MeActive::class);
            $app->get('/find', Cart\Find::class)->add(new \App\Middleware\Validation\Cart\Find())->add(new CheckPermissionAdmin('carts.find.admin'));
            $app->post('/create', Cart\Create::class)->add(new \App\Middleware\Validation\Cart\Create())->add(new CheckPermissionAdmin('carts.create.admin'));
            $app->post('/update', Cart\Update::class)->add(new \App\Middleware\Validation\Cart\Update())->add(new CheckPermissionAdmin('carts.update.admin'));
            $app->post('/request', Cart\Request::class)->add(new \App\Middleware\Validation\Cart\Request())->add(new CheckPermissionAdmin('carts.request.admin'));
            $app->post('/to_pay', Cart\ToPay::class)->add(new \App\Middleware\Validation\Cart\ToPay())->add(new CheckPermissionAdmin('carts.to_pay.admin'));

            $app->post('/cart_product_delivered', Cart\CartProductDelivered::class)->add(new \App\Middleware\Validation\Cart\CartProductDelivered())->add(new CheckPermissionAdmin('carts.cart_product_delivered.admin'));
            $app->post('/cart_product_canceled', Cart\CartProductCanceled::class)->add(new \App\Middleware\Validation\Cart\CartProductCanceled())->add(new CheckPermissionAdmin('carts.cart_product_canceled.admin'));

        })->add(new CanPermission('carts'))->add(Token::class);

        $app->group('/products_reviews', function (RouteCollectorProxy $app) {

            $app->get('/get_all', ProductReview\GetAll::class)->add(new \App\Middleware\Pagination());
            $app->get('/find', ProductReview\Find::class)->add(new \App\Middleware\Validation\ProductReview\Find());
            $app->post('/create', ProductReview\Create::class)->add(new \App\Middleware\Validation\ProductReview\Create())->add(new CheckPermissionAdmin('products_reviews.create.admin'));
            $app->post('/update', ProductReview\Update::class)->add(new \App\Middleware\Validation\ProductReview\Update())->add(new CheckPermissionAdmin('products_reviews.update.admin'));

        })->add(new CanPermission('products_reviews'))->add(Token::class);

        $app->group('/products', function (RouteCollectorProxy $app) {
        
            $app->get('/get_all', Product\GetAll::class)->add(new \App\Middleware\Pagination())->add(new CheckPermissionAdmin('products.get_all.admin'));
            $app->get('/find', Product\Find::class)->add(new \App\Middleware\Validation\Product\Find())->add(new CheckPermissionAdmin('products.find.admin'));
            $app->post('/create', Product\Create::class)->add(new \App\Middleware\Validation\Product\Create())->add(new CheckPermissionAdmin('products.create.admin'));
            $app->post('/update', Product\Update::class)->add(new \App\Middleware\Validation\Product\Update())->add(new CheckPermissionAdmin('products.update.admin'));
            $app->post('/state', Product\State::class)->add(new \App\Middleware\Validation\Product\State())->add(new CheckPermissionAdmin('products.state.admin'));
            
            $app->post('/cart_product_paid', Product\CartProductPaid::class)->add(new \App\Middleware\Validation\Product\CartProductPaid())->add(new CheckPermissionAdmin('products.state.admin'));
            $app->post('/cart_product_sent', Product\CartProductSent::class)->add(new \App\Middleware\Validation\Product\CartProductSent())->add(new CheckPermissionAdmin('products.state.admin'));
            $app->post('/cart_product_finalized', Product\CartProductFinalized::class)->add(new \App\Middleware\Validation\Product\CartProductFinalized())->add(new CheckPermissionAdmin('products.state.admin'));
            $app->post('/cart_product_canceled', Cart\CartProductCanceled::class)->add(new \App\Middleware\Validation\Cart\CartProductCanceled())->add(new CheckPermissionAdmin('carts.cart_product_canceled.admin'));

        })->add(new CanPermission('products'))->add(Token::class);

        $app->group('/discounts', function (RouteCollectorProxy $app) {
        
            $app->get('/get_all', Discount\GetAll::class)->add(new \App\Middleware\Pagination())->add(new CheckPermissionAdmin('discounts.get_all.admin'));
            $app->get('/find', Discount\Find::class)->add(new \App\Middleware\Validation\Product\Find())->add(new CheckPermissionAdmin('discounts.find.admin'));
            $app->post('/create', Discount\Create::class)->add(new \App\Middleware\Validation\Discount\Create())->add(new CheckPermissionAdmin('discounts.create.admin'));
            $app->post('/update', Discount\Update::class)->add(new \App\Middleware\Validation\Product\Update())->add(new CheckPermissionAdmin('discounts.update.admin'));
            $app->post('/state', Discount\State::class)->add(new \App\Middleware\Validation\Product\State())->add(new CheckPermissionAdmin('discounts.state.admin'));
            
        })->add(new CanPermission('discounts'))->add(Token::class);

        $app->group('/products_categories', function (RouteCollectorProxy $app) {
            
            $app->get('/get_all', ProductCategory\GetAll::class)->add(new \App\Middleware\Pagination())->add(new CheckPermissionAdmin('products_categories.get_all.admin'));
            $app->get('/find', ProductCategory\Find::class)->add(new \App\Middleware\Validation\ProductCategory\Find())->add(new CheckPermissionAdmin('products_categories.find.admin'));
            $app->post('/create', ProductCategory\Create::class)->add(new \App\Middleware\Validation\ProductCategory\Create())->add(new CheckPermissionAdmin('products_categories.create.admin'));
            $app->post('/update', ProductCategory\Update::class)->add(new \App\Middleware\Validation\ProductCategory\Update())->add(new CheckPermissionAdmin('products_categories.update.admin'));
            $app->post('/state', ProductCategory\State::class)->add(new \App\Middleware\Validation\ProductCategory\State())->add(new CheckPermissionAdmin('products_categories.state.admin'));

        })->add(new CanPermission('products_categories'))->add(Token::class);

        $app->group('/migrations', function (RouteCollectorProxy $app) {
            $app->get('/up', Migration\Up::class);
            $app->get('/down', Migration\Down::class)->add(new CanPermission('migrations'))->add(Token::class);
        });

        $app->group('/my-profile', function (RouteCollectorProxy $app) {
            
            $app->get('/get_info', MyProfile\GetInfo::class);
            $app->post('/change_password', MyProfile\ChangePassword::class)->add(new \App\Middleware\Validation\MyProfile\ChangePassword());
            $app->post('/update', MyProfile\Update::class)->add(new \App\Middleware\Validation\MyProfile\Update());

        })->add(Token::class);

        $app->group('/users', function (RouteCollectorProxy $app) {

            $app->get('/get_all', User\GetAll::class)->add(new \App\Middleware\Pagination());
            $app->get('/find', User\Find::class)->add(new \App\Middleware\Validation\User\Find());
            $app->post('/create', User\Create::class)->add(new \App\Middleware\Validation\User\Create());
            $app->post('/update', User\Update::class)->add(new \App\Middleware\Validation\User\Update());
            $app->post('/state', User\State::class)->add(new \App\Middleware\Validation\User\State());

        })->add(new CanPermission('users'))->add(Token::class);

        $app->group('/roles', function (RouteCollectorProxy $app) {
            
            $app->get('/get_all', Role\GetAll::class)->add(new \App\Middleware\Pagination());
            $app->get('/find', Role\Find::class)->add(new \App\Middleware\Validation\Role\Find());
            $app->post('/create', Role\Create::class)->add(new \App\Middleware\Validation\Role\Create());
            $app->post('/update', Role\Update::class)->add(new \App\Middleware\Validation\Role\Update());
            $app->post('/state', Role\State::class)->add(new \App\Middleware\Validation\Role\State());

            $app->post('/assign_permission', Role\AssignPermission::class)->add(new \App\Middleware\Validation\Role\AssignPermission());
            $app->post('/assign_permissions', Role\AssignPermissions::class)->add(new \App\Middleware\Validation\Role\AssignPermissions());

        })->add(new CanPermission('roles'))->add(Token::class);

        $app->group('/permissions', function (RouteCollectorProxy $app) {

            $app->get('/get_all', Permission\GetAll::class)->add(new \App\Middleware\Pagination());
            $app->get('/find', Permission\Find::class)->add(new \App\Middleware\Validation\Permission\Find());
            $app->post('/create', Permission\Create::class)->add(new \App\Middleware\Validation\Permission\Create());
            $app->post('/update', Permission\Update::class)->add(new \App\Middleware\Validation\Permission\Update());
            $app->post('/state', Permission\State::class)->add(new \App\Middleware\Validation\Permission\State());

        })->add(new CanPermission('permissions'))->add(Token::class);

    })->add(BodyParser::class);
} catch (\Throwable $th) {
    Logger::error(
        message: [
            'message' => $th->getMessage(),
            'file' => $th->getFile(),
            'line' => $th->getLine(),
        ],
    );
}