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

namespace Chevere\Tests;

use Chevere\Schwager\DocumentSchema;
use Chevere\Schwager\ServerSchema;
use Chevere\Schwager\Spec;
use Chevere\SchwagerHTML\Html;
use PHPUnit\Framework\TestCase;
use function Chevere\Filesystem\fileForPath;
use function Chevere\Router\router;

final class HtmlTest extends TestCase
{
    public function testConstruct(): void
    {
        $routes = require __DIR__ . '/../demo/routes.php';
        $router = router($routes);
        $document = new DocumentSchema(
            api: 'schwager',
            name: '🐶 Schwager Petstore',
            version: '1.0.0'
        );
        $testServer = new ServerSchema(
            url: 'demoServerUrl',
            description: 'This is a sample server Petstore API spec.'
        );
        $spec = new Spec($router, $document, $testServer);
        $html = new Html($spec);
        $this->assertStringEqualsFile(
            __DIR__ . '/../demo/schwager.html',
            $html->__toString()
        );
        // $this->expectNotToPerformAssertions();
        // $file = fileForPath(__DIR__ . '/schwager.html');
        // $file->createIfNotExists();
        // $file->put($html->__toString());
        // vdd($html->__toString());
    }
}
