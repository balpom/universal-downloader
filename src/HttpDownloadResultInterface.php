<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

interface HttpDownloadResultInterface extends DownloadResultInterface
{

    /**
     * Get content date.
     */
    public function date(): int|false;

    /**
     * Get content MIME type.
     */
    public function mime(): string|false;

    /**
     * Get content HTTP code.
     */
    public function code(): int|false;
}
