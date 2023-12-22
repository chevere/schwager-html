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
use Chevere\Http\Status;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\null;

#[Description('Logs out current logged in user session')]
#[Response(
    new Status(200),
)]
final class UserLogoutGetController extends Controller
{
    public static function return(): ParameterInterface
    {
        return null();
    }

    public function main(): void
    {
    }
}
