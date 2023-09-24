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
use Chevere\Parameter\Interfaces\ArrayParameterInterface;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\enum;
use function Chevere\Parameter\generic;
use function Chevere\Parameter\integer;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;

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
            id: integer(),
            category: arrayp(
                id: integer(),
                name: string(),
            ),
            name: string(),
            photoUrls: generic(string()),
            tags: generic(
                arrayp(
                    id: integer(),
                    name: string(),
                )
            ),
            status: enum('available', 'pending', 'sold'),
        );
    }

    public static function acceptResponse(): ParameterInterface
    {
        return null();
    }

    public function run(): void
    {
    }
}
