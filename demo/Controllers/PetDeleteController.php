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
use Chevere\Parameter\Interfaces\ArrayParameterInterface;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;

#[Description('Deletes a pet')]
#[Response(
    new Status(400, 404),
    new Header('Content-Type', 'application/json')
)]
final class PetDeleteController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp(
            api_key: string(), // TODO: Should be at header
        );
    }

    public static function return(): ParameterInterface
    {
        return null();
    }

    public function main(
        #[StringAttr(description: 'Pet id to delete')]
        string $petId
    ): void {
    }
}
