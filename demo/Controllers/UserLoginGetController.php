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
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\string;

#[Description('Logs user into the system')]
#[Response(
    new Status(200, 400),
    new Header('Content-Type', 'application/json'),
    new Header('X-Expires-After', '{UTC}'),
    new Header('X-Rate-Limit', '{rateLimit}')
)]
final class UserLoginGetController extends Controller
{
    public static function return(): ParameterInterface
    {
        return string();
    }

    public function main(): string
    {
        return '';
    }
}
