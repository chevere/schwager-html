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
use function Chevere\Parameter\int;
use function Chevere\Parameter\string;

#[Description('uploads an image')]
#[Response(
    new Status(200),
    new Header('Content-Type', 'application/json')
)]
final class UploadImagePostController extends Controller
{
    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp(
            additionalMetadata: string(description: 'Additional data to pass to server'),
            file: string(),
        );
    }

    public static function return(): ParameterInterface
    {
        return arrayp(
            code: int(),
            type: string(),
            message: string(),
        );
    }

    public function run(
        #[StringAttr(description: 'ID of pet to update')]
        string $petId
    ): array {
        return [];
    }
}
