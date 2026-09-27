<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Balpom\UniversalDownloader\Result\HttpDownloadResultInterface;
use \Throwable;

abstract class AbstractHttpDownloader extends AbstractDownloader implements HttpDownloadInterface
{

    abstract public function get(string $uri, string|array|null $headers = null): DownloadInterface;

    abstract public function head(string $uri, string|array|null $headers = null): HttpDownloadInterface;

    abstract public function post(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    abstract public function put(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    abstract public function patch(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface;

    abstract public function delete(string $uri, string|array|null $headers = null): HttpDownloadInterface;

    abstract public function result(): HttpDownloadResultInterface;

    protected function getLocation(): string|false
    {
        try {
            $location = $this->response->getHeader('Location');
        } catch (Throwable $e) {
            throw new DownloaderException("Error: unable to get redirect location.");
        }

        return isset($location[0]) ? $location[0] : false;
    }

    protected function getHeaderName(string $header): string
    {
        $pos = strpos($header, ':');

        return trim(substr($header, 0, $pos));
    }

    protected function getHeaderValue(string $header): string
    {
        $pos = strpos($header, ':') + 1;

        return trim(substr($header, $pos));
    }

}