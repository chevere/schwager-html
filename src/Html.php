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

namespace Chevere\SchwagerUI;

use Chevere\Schwager\Spec;
use Chevere\Throwable\Exceptions\InvalidArgumentException;
use Chevere\Throwable\Exceptions\LogicException;
use Stringable;
use function Chevere\Standard\arrayUnsetKey;

final class Html implements Stringable
{
    public const TEMPLATES_DIR = __DIR__ . '/Template/';

    private string $html;

    private string $descriptionHtml;

    private string $pathHtml;

    private string $variableHtml;

    private string $variableNameHtml;

    private string $statusCodeHtml;

    private string $variablesHtml;

    private string $requestHtml;

    private string $responseHtml;

    private string $responseDescriptionHtml;

    private string $responseListHtml;

    private string $endpointHtml;

    private string $endpointsHtml;

    private string $badgeHtml;

    private string $serverHtml;

    private string $serversHtml;

    private string $descriptionList;

    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        private Spec $spec,
        private array $array = []
    ) {
        $this->onConstruct();
        $servers = '';
        foreach ($this->spec->servers() as $server) {
            $search = [
                '%url%',
                '%description%',
            ];
            $replace = [
                $server->url,
                $server->description,
            ];
            $servers .= str_replace($search, $replace, $this->serverHtml);
        }
        $servers = str_replace('%servers%', $servers, $this->serversHtml);
        $this->html = str_replace('%servers.html%', $servers, $this->html);
        $paths = '';
        foreach ($this->array['paths'] as $uri => $path) {
            $variables = $this->variables($path['variables'] ?? []);
            $endpoints = $this->endpoints($path['name'], $path['endpoints']);
            $search = [
                '%path%',
                '%name%',
                '%regex%',
                '%variables.html%',
                '%endpoints.html%',
            ];
            $replace = [
                $uri,
                $path['name'],
                $path['regex'],
                $variables,
                $endpoints,
            ];
            $paths .= str_replace($search, $replace, $this->pathHtml);
        }
        $this->html = str_replace('%paths.html%', $paths, $this->html);
    }

    public function __toString()
    {
        return $this->html;
    }

    /**
     * @param array<string, array<string, null|string|bool>> $variables
     */
    private function variables(array $variables): string
    {
        $return = '';
        foreach ($variables as $name => $variable) {
            $search = [
                '%name%',
                '%type%',
                '%regex%',
                '%description%',
            ];
            /** @var string */
            $type = $variable['type'] ?? '';
            /** @var string */
            $regex = $variable['regex'] ?? '';
            /** @var string */
            $description = $variable['description'] ?? '';
            $replace = [
                str_replace('%name%', $name, $this->variableNameHtml),
                $this->description('Type', $this->type($type)),
                $this->description('Regex', $this->code($regex)),
                $this->description('Description', $description),
            ];
            $return .= str_replace($search, $replace, $this->variableHtml);
        }

        return str_replace('%variables%', $return, $this->variablesHtml);
    }

    /**
     * @param array<string, array<string, null|string|bool>> $query
     */
    private function query(array $query): string
    {
        $return = '';
        foreach ($query as $name => $string) {
            $properties = '';
            $map = arrayUnsetKey($string, 'required', 'type');
            foreach ($map as $property => $value) {
                $property = (string) $property;
                $properties .= $this->description(
                    $property,
                    (string) ($value ?? '')
                );
            }
            /** @var string */
            $type = $string['type'];
            /** @var boolean */
            $required = $string['required'];
            $return .= $this->description(
                $name,
                $this->type($type)
                    . $this->optional($required)
                    . $this->descriptionList($properties)
            );
        }

        return $return;
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function body(array $body): string
    {
        $type = $body['type'] ?? '';
        if (is_array($type)) {
            $type = ''; // @codeCoverageIgnore
        }
        $return = '';
        if ($type === '') {
            foreach ($body as $property => $value) {
                $required = $value['required'] ?? true;
                $described = $this->body($value);
                $return .= $this->descriptionList(
                    $this->description(
                        $property,
                        $this->type($value['type'])
                        . $this->optional($required)
                    ) . $described
                );
            }

            return $return;
        }
        $parameters = $body['parameters'] ?? [];
        if ($type === 'union') {
            foreach ($parameters as $pos => $param) {
                $return .= $this->description(
                    $this->badge((string) $pos, 'badge-key'),
                    $this->type($param['type'])
                    . $this->body($param)
                );
            }

            return $return;
        }
        if ($type === 'generic') {
            foreach ($parameters as $name => $parameter) {
                $return .= $this->descriptionList(
                    $this->description(
                        $this->badge($name, 'badge-key'),
                        $this->type($parameter['type'])
                        . $this->body($parameter)
                    )
                );
            }

            return $return;
        }

        if (str_starts_with($type, 'array')) {
            return $this->body($parameters);
        }

        return $return;
    }

    private function descriptionList(string $description): string
    {

        if ($description === '') {
            return ''; // @codeCoverageIgnore
        }

        return str_replace('%list%', $description, $this->descriptionList);
    }

    /**
     * @param array<string, array<string, array<string, string>>> $endpoints
     */
    private function endpoints(string $pathId, array $endpoints): string
    {
        $return = '';
        foreach ($endpoints as $method => $endpoint) {
            $request = $this->request($endpoint['request'] ?? []);
            $responses = $this->responses($endpoint['responses'] ?? []);
            $return .= str_replace(
                [
                    '%request.html%', '%responses.html%'],
                [$request, $responses],
                $this->endpointHtml
            );
            $replace = [
                '%method%' => $method,
                '%md5%' => md5($pathId . $method),
                '%description%' => $endpoint['description'],
            ];
            $return = strtr($return, $replace);
        }

        return str_replace('%endpoints%', $return, $this->endpointsHtml);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function request(array $request): string
    {
        $search = [
            '%headers%',
            '%query%',
            '%body%',
        ];
        $headers = $this->headers($request['headers'] ?? []);
        $replace = [
            $this->description('Headers', $headers),
            '',
            '',
        ];
        $query = $this->query($request['query'] ?? []);
        if ($query !== '') {
            $replace[1] = $this->description(
                'Query',
                $this->type('array&lt;string&gt;')
            )
            . $this->descriptionList($query);
        }
        $body = $this->body($request['body'] ?? []);
        if ($body !== '') {
            $replace[2] = $this->description(
                'Body',
                $this->type($request['body']['type'] ?? '')
            )
            . $body;
        }

        return str_replace($search, $replace, $this->requestHtml);
    }

    /**
     * @param array<int, string> $headers
     */
    private function headers(array $headers): string
    {
        $array = [];
        foreach ($headers as $value) {
            $array[] = $value;
        }

        return implode('<br>', $array);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function responses(array $array): string
    {
        $responses = '';
        foreach ($array as $code => $el) {
            $descriptions = '';
            $code = (string) $code;
            $search = [
                '%context%',
                '%headers%',
                '%body%',
            ];
            foreach ($el as $response) {
                $body = $this->body($response['body'] ?? []);
                $headers = $this->headers($response['headers'] ?? []);
                $replace = [
                    $this->description('Context', $response['context'] ?? ''),
                    $this->description('Headers', $headers),
                    '',
                ];
                if ($body !== '') {
                    $replace[2] .= $this->description(
                        'Body',
                        $this->type($response['body']['type'] ?? '')
                        . $this->div($response['body']['description'] ?? '')
                    )
                    . $body;
                }
                $descriptions .= str_replace(
                    $search,
                    $replace,
                    $this->responseDescriptionHtml
                );
            }
            $responses .= str_replace(
                [
                    '%code%',
                    '%responses%',
                ],
                [
                    str_replace('%code%', $code, $this->statusCodeHtml),
                    $descriptions,
                ],
                $this->responseListHtml
            );
        }

        return str_replace('%response-list.html%', $responses, $this->responseHtml);
    }

    private function optional(bool $isRequired): string
    {
        if (! $isRequired) {
            return $this->badge('optional', 'badge-key');
        }

        return '';
    }

    private function type(string $content): string
    {
        return $this->tag('code', 'type d-inline-block me-1', $content);
    }

    private function badge(string $name, string $class = ''): string
    {
        return str_replace(
            [
                '%name%',
                '%class%',
            ],
            [
                $name,
                $class !== ''
                    ? " {$class}"
                    : '',
            ],
            $this->badgeHtml
        );
    }

    private function div(string $content, string $class = ''): string
    {
        return $this->tag('div', $class, $content);
    }

    private function code(string $content, string $class = ''): string
    {
        return $this->tag('code', $class, $content);
    }

    private function getTemplate(string $name): string
    {
        return file_get_contents(self::TEMPLATES_DIR . $name)
            ?: throw new LogicException();
    }

    private function description(string $title, string $description): string
    {
        if ($description === '') {
            return '';
        }

        return str_replace(
            [
                '%title%',
                '%dt%',
                '%dd%',
            ],
            [
                strip_tags($title),
                $title,
                $description,
            ],
            $this->descriptionHtml
        );
    }

    private function onConstruct(): void
    {
        if ($this->array === []) {
            $this->array = $this->spec->toArray();
        }
        $this->html = $this->getTemplate('main.html');
        $search = [
            '%name%',
            '%version%',
        ];
        $replace = [
            $this->spec->document()->name,
            $this->spec->document()->version,
        ];
        $this->html = str_replace($search, $replace, $this->html);
        $this->descriptionHtml = $this->getTemplate('description.html');
        $this->pathHtml = $this->getTemplate('path.html');
        $this->variableHtml = $this->getTemplate('variable.html');
        $this->variableNameHtml = $this->getTemplate('variable-name.html');
        $this->variablesHtml = $this->getTemplate('variables.html');
        $this->requestHtml = $this->getTemplate('request.html');
        $this->responseHtml = $this->getTemplate('response.html');
        $this->responseListHtml = $this->getTemplate('response-list.html');
        $this->responseDescriptionHtml = $this->getTemplate('response-description.html');
        $this->endpointHtml = $this->getTemplate('endpoint.html');
        $this->endpointsHtml = $this->getTemplate('endpoints.html');
        $this->statusCodeHtml = $this->getTemplate('status-code.html');
        $this->badgeHtml = $this->getTemplate('badge.html');
        $this->serverHtml = $this->getTemplate('server.html');
        $this->serversHtml = $this->getTemplate('servers.html');
        $this->descriptionList = $this->getTemplate('description-list.html');
    }

    private function tag(string $tag, string $class, string $content): string
    {
        $attribute = match ($class) {
            '' => '',
            default => <<<HTML
             class="{$class}"
            HTML
        };

        return match ($content) {
            '' => '',
            default => <<<HTML
            <{$tag}{$attribute}>{$content}</{$tag}>
            HTML,
        };
    }
}
