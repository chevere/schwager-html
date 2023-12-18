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
use Chevere\Parameter\Interfaces\ArrayParameterInterface;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\generic;
use function Chevere\Parameter\int;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;

#[Description('Creates lists of users with given input array')]
#[Response(
    new Status(200),
    new Header('Content-Type', 'application/json')
)]
final class UserCreateWithListPostController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp(
            input: generic(
                arrayp(
                    id: int(),
                    username: string(),
                    firstName: string(),
                    lastName: string(),
                    email: string(),
                    password: string(),
                    phone: string(),
                    userStatus: int()
                )
            )
        );
    }

    public static function return(): ParameterInterface
    {
        return null();
    }

    public function run(): void
    {
    }
}
