<?php

namespace Balpom\UniversalDownloader;

use Psr\Http\Message\ResponseInterface;

interface PSR18DownloadResultInterface extends HttpDownloadResultInterface
{

    /**
     * Get response.
     */
    public function response(): ResponseInterface|null;
}
