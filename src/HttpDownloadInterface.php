<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Balpom\UniversalDownloader\Result\HttpDownloadResultInterface;

interface HttpDownloadInterface extends DownloadInterface
{

    /**
     * Get content of web-resource with HEAD method.
     */
    public function head(string $uri, string|array|null $headers = null): HttpDownloadInterface;

    /**
     * Get content of web-resource with POST method.
     */
    public function post(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    /**
     * Get content of web-resource with PUT method.
     */
    public function put(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    /**
     * Get content of web-resource with PATCH method.
     */
    public function patch(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    /**
     * Get content of web-resource with DELETE method.
     */
    public function delete(string $uri, string|array|null $headers = null): HttpDownloadInterface;

    /**
     * Get result of request.
     */
    public function result(): HttpDownloadResultInterface;

}