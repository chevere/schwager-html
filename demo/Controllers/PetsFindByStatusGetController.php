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
use Chevere\Parameter\Interfaces\ArrayStringParameterInterface;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\arrayString;
use function Chevere\Parameter\enum;
use function Chevere\Parameter\generic;
use function Chevere\Parameter\integer;
use function Chevere\Parameter\string;

#[Description('Finds Pets by status')]
#[Response(
    new Status(200, 400),
    new Header('Content-Type', 'application/json')
)]
final class PetsFindByStatusGetController extends Controller
{
    public static function acceptQuery(): ArrayStringParameterInterface
    {
        return arrayString(
            status: enum('available', 'pending', 'sold'),
        );
    }

    public static function acceptResponse(): ParameterInterface
    {
        return generic(
            arrayp(),
            arrayp(
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
            )
        );
    }

    public function run(): array
    {
        return [];
    }
}
