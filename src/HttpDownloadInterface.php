<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

interface HttpDownloadInterface extends DownloadInterface
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
     * Get result of request.
     */
    public function result(): HttpDownloadResultInterface;
}
