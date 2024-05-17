<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

abstract class AbstractDownloader implements DownloadInterface
{

    protected int $attempts = 1; // Number of request attempts.
    protected int $pause = 10; // Pause (in seconds) between request attempts.

    abstract public function get(string $uri): DownloadInterface;

    public function attempts(int $attempts): DownloadInterface
    {
        if (0 > $attempts) {
            throw new DownloaderException("Number of request attempts must be positive.");
        }
        if (10 < $attempts) {
            throw new DownloaderException("Number of request attempts must be less than or equal 10.");
        }
        $this->attempts = $attempts;

        return $this;
    }

    public function pause(int $seconds): DownloadInterface
    {
        if (0 > $seconds) {
            $seconds = 0;
        }
        $this->pause = $seconds;

        return $this;
    }
}
