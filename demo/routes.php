<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Chevere\Demo\Controllers\PetDeleteController;
use Chevere\Demo\Controllers\PetGetController;
use Chevere\Demo\Controllers\PetPostController;
use Chevere\Demo\Controllers\PetPutController;
use Chevere\Demo\Controllers\PetsFindByStatusGetController;
use Chevere\Demo\Controllers\PetsFindByTagGetController;
use Chevere\Demo\Controllers\PetsPostController;
use Chevere\Demo\Controllers\StoreInventoryGetController;
use Chevere\Demo\Controllers\StoreOrderDeleteController;
use Chevere\Demo\Controllers\StoreOrderGetController;
use Chevere\Demo\Controllers\StoreOrderPostController;
use Chevere\Demo\Controllers\UploadImagePostController;
use Chevere\Demo\Controllers\UserCreateWithArrayPostController;
use Chevere\Demo\Controllers\UserCreateWithListPostController;
use Chevere\Demo\Controllers\UserDeleteController;
use Chevere\Demo\Controllers\UserGetController;
use Chevere\Demo\Controllers\UserLoginGetController;
use Chevere\Demo\Controllers\UserLogoutGetController;
use Chevere\Demo\Controllers\UserPutController;
use Chevere\Demo\Controllers\UsersPostController;
use function Chevere\Router\route;
use function Chevere\Router\routes;

return routes(
    route(
        path: '/pet/{petId}/uploadImage',
        POST: UploadImagePostController::class,
    ),
    route(
        path: '/pet',
        POST: PetsPostController::class,
        PUT: PetPutController::class,
    ),
    route(
        path: '/pet/findByStatus',
        GET: PetsFindByStatusGetController::class,
    ),
    route(
        path: '/pet/findByTags',
        GET: PetsFindByTagGetController::class,
    ),
    route(
        path: '/pet/{petId}',
        GET: PetGetController::class,
        POST: PetPostController::class,
        DELETE: PetDeleteController::class,
    ),
    route(
        path: '/store/order',
        POST: StoreOrderPostController::class,
    ),
    route(
        path: '/store/order/{orderId}',
        GET: StoreOrderGetController::class,
        DELETE: StoreOrderDeleteController::class,
    ),
    route(
        path: '/store/inventory',
        GET: StoreInventoryGetController::class,
    ),
    route(
        path: '/user/createWithArray',
        POST: UserCreateWithArrayPostController::class,
    ),
    route(
        path: '/user/createWithList',
        POST: UserCreateWithListPostController::class,
    ),
    route(
        path: '/user/{username}',
        GET: UserGetController::class,
        PUT: UserPutController::class,
        DELETE: UserDeleteController::class,
    ),
    route(
        path: '/user',
        POST: UsersPostController::class,
    ),
    route(
        path: '/login',
        GET: UserLoginGetController::class,
    ),
    route(
        path: '/logout',
        GET: UserLogoutGetController::class,
    ),
);
