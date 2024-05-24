<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

class SimpleDownloader extends AbstractDownloader implements DownloadInterface, DownloadResultInterface
{

    private string|false|null $content = null;

    public function get(string $uri): DownloadInterface
    {
        $this->content = false;
        $attempt = 0;
        $redirects = 0;
        do {
            $attempt++;
            try {
                $content = @file_get_contents($uri);
            } catch (Exception $e) { // Not doing anything.
            }
            if (false !== $content) {
                $this->content = $content;
                break;
            }
        } while ($attempt <= $this->attempts);

        return $this;
    }

    public function content(): string|false
    {
        return (null !== $this->content) ? $this->content : false;
    }
}
