<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\Result;

use Psr\Http\Message\ResponseInterface;

interface PSR18DownloadResultInterface extends HttpDownloadResultInterface
{

    /**
     * Get response.
     */
    public function response(): ResponseInterface|null;

}