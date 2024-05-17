<?php

namespace Balpom\UniversalDownloader;

interface DownloadResultInterface
{

    /**
     * Get content.
     */
    public function content(): string|false;
}
