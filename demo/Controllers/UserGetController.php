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

namespace Chevere\Demo\Controllers;

use Chevere\Http\Attributes\Description;
use Chevere\Http\Attributes\Response;
use Chevere\Http\Controller;
use Chevere\Http\Header;
use Chevere\Http\Status;
use Chevere\Parameter\Attributes\StringAttr;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\int;
use function Chevere\Parameter\string;

#[Description('Get user by user name')]
#[Response(
    new Status(200, 400, 404),
    new Header('Content-Type', 'application/json')
)]
final class UserGetController extends Controller
{
    public static function acceptResponse(): ParameterInterface
    {
        return arrayp(
            id: int(),
            username: string(),
            firstName: string(),
            lastName: string(),
            email: string(),
            password: string(),
            phone: string(),
            userStatus: int()
        );
    }

    public function run(
        #[StringAttr(description: 'The name that needs to be fetched')]
        string $username
    ): array {
        return [];
    }
}
