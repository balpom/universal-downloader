<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

abstract class AbstractHttpDownloader extends AbstractDownloader implements HttpDownloadInterface
{

    abstract public function get(string $uri): DownloadInterface;

    abstract public function head(string $uri): HttpDownloadInterface;

    abstract public function post(string $uri, array $data = []): HttpDownloadInterface;

    abstract public function result(): HttpDownloadResultInterface;
}
