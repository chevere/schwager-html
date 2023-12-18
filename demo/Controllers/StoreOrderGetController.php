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
use function Chevere\Parameter\bool;
use function Chevere\Parameter\datetime;
use function Chevere\Parameter\enum;
use function Chevere\Parameter\int;

#[Description('Find purchase order by ID')]
#[Response(
    new Status(200, 400, 404),
    new Header('Content-Type', 'application/json')
)]
final class StoreOrderGetController extends Controller
{
    public static function return(): ParameterInterface
    {
        return arrayp(
            id: int(),
            petId: int(),
            quantity: int(),
            shipDate: datetime(),
            status: enum('placed', 'approved', 'delivered'),
            complete: bool()
        );
    }

    public function run(
        #[StringAttr(description: 'ID of pet that needs to be fetched')]
        string $orderId
    ): array {
        return [];
    }
}
