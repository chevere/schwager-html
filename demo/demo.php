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

use Chevere\Schwager\DocumentSchema;
use Chevere\Schwager\ServerSchema;
use Chevere\Schwager\Spec;
use Chevere\SchwagerHTML\Html;
use function Chevere\Router\router;

foreach (['/../', '/../../../../'] as $path) {
    $autoload = __DIR__ . $path . 'vendor/autoload.php';
    if (stream_resolve_include_path($autoload)) {
        require $autoload;

        break;
    }
}

$routes = require __DIR__ . '/../demo/routes.php';
$router = router($routes);
$document = new DocumentSchema(
    api: 'schwager',
    name: '🐶 Schwager Petstore',
    version: '1.0.0'
);
$demoServer = new ServerSchema(
    url: 'demoServerUrl',
    description: 'This is a sample server Petstore API spec.'
);
$otherServer = new ServerSchema(
    url: 'otherServerUrl',
    description: 'This is a another URL.'
);
$spec = new Spec($router, $document, $demoServer, $otherServer);
$html = new Html($spec);
$file = __DIR__ . '/output/schwager.html';
file_put_contents($file, $html->__toString());
echo <<<PLAIN
[OK] {$file}

PLAIN;
passthru("open {$file}");
