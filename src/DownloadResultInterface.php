<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

interface DownloadResultInterface
{

    /**
     * Get content.
     */
    public function content(): string|false;
}
