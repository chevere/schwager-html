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
use function Chevere\Parameter\enum;
use function Chevere\Parameter\int;
use function Chevere\Parameter\iterable;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;
use function Chevere\Parameter\union;

#[Description('Update an existing pet')]
#[Response(
    new Status(400, 404, 405),
    new Header('Content-Type', 'application/json')
)]
final class PetPutController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp(
            id: int(),
            category: arrayp(
                id: int(),
                name: string(),
            ),
            name: string(),
            photoUrls: union(
                arrayp(),
                iterable(string())
            ),
            tags: union(
                arrayp(),
                iterable(
                    arrayp(
                        id: int(),
                        name: string(),
                    )
                )
            ),
            status: enum('available', 'pending', 'sold'),
        );
    }

    public static function return(): ParameterInterface
    {
        return null();
    }

    public function main(): void
    {
    }
}
