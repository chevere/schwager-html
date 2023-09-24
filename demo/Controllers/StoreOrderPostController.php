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
use function Chevere\Parameter\boolean;
use function Chevere\Parameter\datetime;
use function Chevere\Parameter\enum;
use function Chevere\Parameter\integer;

#[Description('Place an order for a pet')]
#[Response(
    new Status(200, 400),
    new Header('Content-Type', 'application/json')
)]
final class StoreOrderPostController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp(
            id: integer(),
            petId: integer(),
            quantity: integer(),
            shipDate: datetime(),
            status: enum('placed', 'approved', 'delivered'),
            complete: boolean()
        );
    }

    public static function acceptResponse(): ParameterInterface
    {
        return arrayp(
            id: integer(),
            petId: integer(),
            quantity: integer(),
            shipDate: datetime(),
            status: enum('placed', 'approved', 'delivered'),
            complete: boolean()
        );
    }

    public function run(): array
    {
        return [];
    }
}
