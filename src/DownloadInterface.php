<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

interface DownloadInterface
{

    /**
     * Set max number of request attempts.
     */
    public function attempts(int $attempts): DownloadInterface;

    /**
     * Set pause time between request attempts.
     */
    public function pause(int $seconds): DownloadInterface;

    /**
     * Set connection timeout.
     */
    public function timeout(int $seconds): DownloadInterface;

    /**
     * Requests content of resource and saves it in an internal variable.
     * Resource may be either local or remote file or WEB-resource.
     * For WEB-resource request is being made with GET method.
     */
    public function get(string $uri, string|array|null $headers = null): DownloadInterface;

}