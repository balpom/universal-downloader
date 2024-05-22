<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

class Result implements DownloadResultInterface
{

    private string|false $content;

    public function __construct(string|false $content = false)
    {
        $this->setContent($content);
    }

    public function content(): string|false
    {
        return $this->content;
    }

    protected function setContent(string|false $content): void
    {
        $this->content = $content;
    }
}
