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
use function Chevere\Parameter\generic;
use function Chevere\Parameter\int;
use function Chevere\Parameter\string;

#[Description('Returns pet inventories by status')]
#[Response(
    new Status(200),
    new Header('Content-Type', 'application/json')
)]
final class StoreInventoryGetController extends Controller
{
    public static function return(): ParameterInterface
    {
        return generic(
            K: string(),
            V: int(),
        );
    }

    public function run(
    ): array {
        return [];
    }
}
