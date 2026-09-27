<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\Result;

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

    /**
     * Get content HTTP headers.
     */
    public function headers(): array|false;

    /**
     * Get content charset (if exist, otherwise false).
     */
    public function charset(): string|false;

}