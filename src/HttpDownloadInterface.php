<?php

namespace Balpom\UniversalDownloader;

use Psr\Http\Client\ClientInterface;

interface HttpDownloadInterface extends DownloadInterface, ClientInterface
{

    /**
     * Get content of web-resource with HEAD method.
     */
    public function head(string $uri): HttpDownloadInterface;

    /**
     * Get content of web-resource with POST method.
     */
    public function post(string $uri, array $data = []): HttpDownloadInterface;

    /**
     * Get content date from last request result.
     */
    public function date(): int;

    /**
     * Get content MIME type from last request result.
     */
    public function mime(): string|false;

    /**
     * Get HTTP code of last request result.
     */
    public function code(): int;
}
