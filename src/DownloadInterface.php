<?php

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
     * Make request content of resource and save it into internal variable.
     * Resource may be either local or remote file or WEB-resource.
     * For WEB-resource request is being made with GET method.
     */
    public function get(string $uri): DownloadInterface;
}
