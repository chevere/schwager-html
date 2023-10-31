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
use function Chevere\Parameter\int;
use function Chevere\Parameter\string;
use function Chevere\Parameter\union;

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
        return union(
            arrayp(),
            generic(
                arrayp(
                    id: int(),
                    category: arrayp(
                        id: int(),
                        name: string(),
                    ),
                    name: string(),
                    photoUrls: union(
                        arrayp(),
                        generic(string())
                    ),
                    tags: union(
                        arrayp(),
                        generic(
                            arrayp(
                                id: int(),
                                name: string(),
                            )
                        )
                    ),
                    status: enum('available', 'pending', 'sold'),
                )
            )
        );
    }

    public function run(): array
    {
        return [];
    }
}
