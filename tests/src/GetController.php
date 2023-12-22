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

namespace Chevere\Tests\src;

use Chevere\Http\Attributes\Request;
use Chevere\Http\Attributes\Response;
use Chevere\Http\Controller;
use Chevere\Http\Header;
use Chevere\Http\Status;
use Chevere\Parameter\Attributes\StringAttr;
use Chevere\Parameter\Interfaces\ArrayParameterInterface;
use Chevere\Parameter\Interfaces\ArrayStringParameterInterface;
use Chevere\Parameter\Interfaces\ParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\arrayString;
use function Chevere\Parameter\date;
use function Chevere\Parameter\float;
use function Chevere\Parameter\generic;
use function Chevere\Parameter\int;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;
use function Chevere\Parameter\time;
use function Chevere\Parameter\union;

#[Request(
    new Header('Content-Type', 'application/json'),
)]
#[Response(
    new Status(222, 403),
    new Header('Content-Type', 'application/json'),
    new Header('Foo', 'Bar'),
)]
class GetController extends Controller
{
    public static function acceptQuery(): ArrayStringParameterInterface
    {
        return arrayString(
            date: date(),
        )->withOptional(
            time: time()
        );
    }

    public static function acceptBody(): ArrayParameterInterface
    {
        return arrayp()->withOptional(
            arreglo: arrayp(
                string: string(),
                integer: int(),
            ),
            rate: float(min: 16.5),
            hours: int(min: 1, max: 8),
            union: union(null(), string()),
            generic: generic(
                string(),
            )
        );
    }

    public static function return(): ParameterInterface
    {
        return arrayp(test: string('/^test$/'));
    }

    public function main(
        #[StringAttr('/^[0-9]+$/', 'The user integer id')]
        string $id,
        #[StringAttr('/^[\w]+$/', 'The user name')]
        string $name
    ): array {
        return [
            'test' => 'test',
        ];
    }
}
