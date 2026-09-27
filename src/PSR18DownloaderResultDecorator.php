<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Balpom\UniversalDownloader\Result\PSR18DownloadResultInterface;

class PSR18DownloaderResultDecorator extends HttpDownloaderResultDecorator implements PSR18DownloadInterface, PSR18DownloadResultInterface
{

    public function __construct(PSR18DownloadInterface $downloader)
    {
        $this->downloader = $downloader;
    }

    public function response(): ResponseInterface|null
    {
        return $this->downloader->response();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->downloader->sendRequest($request);
    }

    public function content(): string|false
    {
        return $this->downloader->result()->content();
    }

}