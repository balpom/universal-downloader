<?php

namespace Balpom\UniversalDownloader;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

interface PSR18DownloadInterface extends DownloadInterface, ClientInterface
{

    /**
     * Get content of web-resource with HEAD method.
     */
    public function head(string $uri): PSR18DownloadInterface;

    /**
     * Get content of web-resource with POST method.
     */
    public function post(string $uri, array $data = []): PSR18DownloadInterface;

    /**
     * Get content of last request result.
     */
    public function response(): ResponseInterface|null;

    /**
     * Get HTTP code of last request result.
     */
    public function code(): int;
}
