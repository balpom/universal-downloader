<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Balpom\UniversalDownloader\Result\HttpDownloadResultInterface;

class HttpDownloaderResultDecorator implements HttpDownloadInterface, HttpDownloadResultInterface
{
    protected HttpDownloadInterface $downloader;

    public function __construct(HttpDownloadInterface $downloader)
    {
        $this->downloader = $downloader;
    }

    public function attempts(int $attempts): DownloadInterface
    {
        $this->downloader->attempts($attempts);
        return $this;
    }

    public function pause(int $seconds): DownloadInterface
    {
        $this->downloader->pause($seconds);
        return $this;
    }

    public function timeout(int $seconds): DownloadInterface
    {
        $this->downloader->timeout($seconds);
        return $this;
    }

    public function get(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->get($uri, $headers);
        return $this;
    }

    public function head(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->head($uri, $headers);
        return $this;
    }

    public function post(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->post($uri, $body, $headers);
        return $this;
    }

    public function put(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->put($uri, $body, $headers);
        return $this;
    }

    public function patch(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->patch($uri, $body, $headers);
        return $this;
    }

    public function delete(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        $this->downloader->delete($uri, $headers);
        return $this;
    }

    public function result(): HttpDownloadResultInterface
    {
        $this->downloader->result();
        return $this;
    }

    public function content(): string|false
    {
        return $this->downloader->result()->content();
    }

    public function date(): int|false
    {
        return $this->downloader->result()->date();
    }

    public function mime(): string|false
    {
        return $this->downloader->result()->mime();
    }

    public function code(): int|false
    {
        return $this->downloader->result()->code();
    }

    public function headers(): array|false
    {
        return $this->downloader->result()->headers();
    }

    public function charset(): string|false
    {
        return $this->downloader->result()->charset();
    }

}