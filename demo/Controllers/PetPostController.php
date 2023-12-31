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
use function Chevere\Parameter\enum;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;

#[Description('Updates a pet in the store with form data')]
#[Response(
    new Status(405),
    new Header('Content-Type', 'application/json')
)]
final class PetPostController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp()
            ->withOptional(
                name: string(),
                status: enum('available', 'pending', 'sold')
            )
            ->withOptionalMinimum(1);
    }

    public static function return(): ParameterInterface
    {
        return null();
    }

    public function main(
        #[StringAttr(description: 'ID of pet that needs to be updated')]
        string $petId
    ): void {
    }
}
