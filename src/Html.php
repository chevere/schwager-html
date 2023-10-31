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

namespace Chevere\SchwagerHTML;

use Chevere\Schwager\Spec;
use Chevere\Throwable\Exceptions\LogicException;
use Stringable;
use Symfony\Component\Yaml\Yaml;
use function Chevere\Filesystem\fileForPath;
use function Chevere\Standard\arrayFilterBoth;

final class Html implements Stringable
{
    public const TEMPLATE_DIR = __DIR__ . '/Template/';

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

    private string $serverHtml;

    private string $serversHtml;

    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        private Spec $spec,
        private array $array = []
    ) {
        if ($this->array === []) {
            $this->array = arrayFilterBoth($spec->toArray(), function ($v, $k) {
                return match (true) {
                    $v === null => false,
                    $v === [] => false,
                    $v === '' => false,
                    $k === 'required' && $v === true => false,
                    // $k === 'regex' && $v === '^.*$' => false,
                    $k === 'body' && $v === [
                        'type' => 'array#map',
                    ] => false,
                    default => true,
                };
            });
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
        $this->serverHtml = $this->getTemplate('server.html');
        $this->serversHtml = $this->getTemplate('servers.html');
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
        $this->replaceStyles();
        $this->replaceScripts();
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
            /** @var string $type */
            $type = $variable['type'] ?? '';
            /** @var string $regex */
            $regex = $variable['regex'] ?? '';
            /** @var string $description */
            $description = $variable['description'] ?? '';
            $replace = [
                str_replace('%name%', "{{$name}}", $this->variableNameHtml),
                $this->description('Type', $type),
                $this->description('Regex', $this->code($regex)),
                $this->description('Description', $description),
            ];
            $return .= str_replace($search, $replace, $this->variableHtml);
        }

        return $return === ''
            ? ''
            : str_replace('%variables%', $return, $this->variablesHtml);
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
                ['%request.html%', '%responses.html%'],
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
        $query = $request['query'] ?? '';
        if ($query !== '') {
            $query = Yaml::dump($query, 20);
        }
        if ($query !== '') {
            $replace[2] .= $this->description(
                'Query string',
                <<<HTML
                <pre><code class="language-yaml">{$query}</code></pre>
                HTML
            );
        }
        $body = $request['body'] ?? '';
        if ($body !== '') {
            $body = Yaml::dump($body, 20);
            $replace[2] .= $this->description(
                'Body',
                <<<HTML
                <pre><code class="language-yaml">{$body}</code></pre>
                HTML
            );
        }
        $replace = array_filter($replace);

        return $replace === []
            ? ''
            : str_replace($search, $replace, $this->requestHtml);
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
                $body = $response['body'] ?? '';
                if ($body !== '') {
                    $body = Yaml::dump($body, 20);
                }
                $headers = $this->headers($response['headers'] ?? []);
                $replace = [
                    $this->description('Context', $response['context'] ?? ''),
                    $this->description('Headers', $headers),
                    '',
                ];
                if ($body !== '') {
                    $replace[2] .= $this->description(
                        'Body',
                        <<<HTML
                        <pre><code class="language-yaml">{$body}</code></pre>
                        HTML
                    );
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

    private function code(string $content, string $class = ''): string
    {
        return $this->tag('code', $class, $content);
    }

    private function getTemplate(string $name): string
    {
        return file_get_contents(self::TEMPLATE_DIR . $name)
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

    private function replaceStyles(): void
    {
        preg_match_all(
            '#<link rel="stylesheet".*(href=\"(.*)\")>#',
            $this->html,
            $files
        );
        foreach ($files[0] as $pos => $match) {
            $fileMatch = fileForPath(self::TEMPLATE_DIR . $files[2][$pos]);
            $replace = '<style media="all">' . $fileMatch->getContents() . '</style>';
            $this->replace($match, $replace);
        }
    }

    private function replaceScripts(): void
    {
        preg_match_all("#<script .*(src=\"(.*)\")><\/script>#", $this->html, $files);
        foreach ($files[0] as $pos => $match) {
            $fileMatch = fileForPath(self::TEMPLATE_DIR . $files[2][$pos]);
            /** @var string $replace */
            $replace = str_replace(' ' . $files[1][$pos], '', $match);
            $replace = str_replace(
                '></script>',
                '>'
                    . $fileMatch->getContents()
                    . '</script>',
                $replace
            );
            $this->replace($match, $replace);
        }
    }

    private function replace(string $search, string $replace): void
    {
        $this->html = str_replace($search, $replace, $this->html);
    }
}
