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

use Chevere\Attributes\Description;
use Chevere\Http\Attributes\Response;
use Chevere\Http\Controller;
use Chevere\Http\Header;
use Chevere\Http\Status;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\null;

#[Description('Delete user')]
#[Response(
    new Status(400, 404),
    new Header('Content-Type', 'application/json')
)]
final class UserDeleteController extends Controller
{
    public static function acceptResponse(): ParameterInterface
    {
        return null();
    }

    public function run(
        #[Description('The name that needs to be deleted')]
        string $username
    ): void {
    }
}
