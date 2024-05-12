<?php

namespace Balpom\UniversalDownloader;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

interface PSR18DownloadInterface extends HttpDownloadInterface
{

    /**
     * Get last response.
     */
    public function response(): ResponseInterface|null;
}
