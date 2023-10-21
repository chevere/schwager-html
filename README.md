# Schwager HTML

> 🔔 Subscribe to the [newsletter](https://chv.to/chevere-newsletter) to don't miss any update regarding Chevere.

![Chevere](chevere.svg)

[![Build](https://img.shields.io/github/actions/workflow/status/chevere/schwager-html/test.yml?branch=0.1&style=flat-square)](https://github.com/chevere/schwager-html/actions)
![Code size](https://img.shields.io/github/languages/code-size/chevere/schwager-html?style=flat-square)
[![Apache-2.0](https://img.shields.io/github/license/chevere/schwager-html?style=flat-square)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-blueviolet?style=flat-square)](https://phpstan.org/)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fchevere%2Fschwager-html%2F0.1)](https://dashboard.stryker-mutator.io/reports/github.com/chevere/schwager-html/0.1)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=alert_status)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=security_rating)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=coverage)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=chevere_schwager-html&metric=sqale_index)](https://sonarcloud.io/dashboard?id=chevere_schwager-html)
[![CodeFactor](https://www.codefactor.io/repository/github/chevere/schwager-html/badge)](https://www.codefactor.io/repository/github/chevere/schwager-html)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/7a4696eb74904dd4bacbd139e2add47e)](https://app.codacy.com/gh/chevere/schwager-html/dashboard)

## Demo

![Schwager HTML light](demo/output/schwager-html-light.webp)
![Schwager HTML dark](demo/output/schwager-html-dark.webp)

There's an [online demo](https://chevere.github.io/schwager-html/demo/output/schwager.html) you can checkout. This is generated from the script at [demo/demo.php](demo/demo.php)

## Quick start

* Install using [Composer](https://packagist.org/packages/chevere/schwager-html)

```php
composer require chevere/schwager-html
```

* Generate HTML

```php
use Chevere\Schwager\DocumentSchema;
use Chevere\Schwager\ServerSchema;
use Chevere\Schwager\Spec;
use Chevere\SchwagerHTML\Html;
use function Chevere\Router\router;

// Load your router
$routes = require 'routes.php';
$router = router($routes);
// Create document
$document = new DocumentSchema(
    api: 'schwager',
    name: '🐶 Schwager Petstore',
    version: '1.0.0'
);
// Create server
$testServer = new ServerSchema(
    url: 'demoServerUrl',
    description: 'This is a sample server Petstore API spec.'
);
// Create spec
$spec = new Spec($router, $document, $testServer);
// Create html
$html = new Html($spec);
// Read html as string
$html->__toString();
```

## Documentation

Documentation is available at [chevere.org](https://chevere.org/packages/schwager.html).

## License

Copyright 2023 [Rodolfo Berrios A.](https://rodolfoberrios.com/)

Chevere is licensed under the Apache License, Version 2.0. See [LICENSE](LICENSE) for the full license text.

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
