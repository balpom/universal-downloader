<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Psr\Http\Message\ResponseInterface;

class PSR18Result extends HttpResult implements PSR18DownloadResultInterface
{

    private ResponseInterface|null $response;

    public function __construct(ResponseInterface|null $response = null)
    {
        $this->response = $response;
        $content = $this->getContent();
        $this->setContent($content);
        $date = $this->getDate();
        $this->setDate($date);
        $mime = $this->getMime();
        $this->setMime($mime);
        $code = $this->getCode();
        $this->setCode($code);
    }

    public function response(): ResponseInterface|null
    {
        if (null !== $this->response) {
            $this->response->getBody()->rewind(); // Just in case...
        }
        return $this->response;
    }

    private function getContent(): string|false
    {
        $response = $this->response();
        if (null === $response || 200 !== $this->getCode()) {
            return false;
        }

        try {
            $content = $response->getBody()->getContents();
        } catch (Exception $e) {
            return false;
        }

        return $content;
    }

    private function getCode(): int|false
    {
        $response = $this->response();
        if (null === $response) {
            return false;
        }

        try {
            $code = $response->getStatusCode();
        } catch (Exception $e) {
            return false;
        }

        return $code;
    }

    private function getMime(): string|false
    {
        $response = $this->response();
        if (null === $response) {
            return false;
        }

        try {
            $mime = $response->getHeaderLine('Content-Type');
        } catch (Exception $e) {
            return false;
        }

        if (empty($mime)) {
            return false;
        }

        $mime = strtolower($mime);
        $mime = explode(';', $mime, 2);
        $mime = trim($mime[0]);

        return empty($mime) ? false : $mime;
    }

    private function getDate(): int|false
    {
        $response = $this->response();
        if (null === $response) {
            return false;
        }

        try {
            $date = $response->getHeaderLine('Last-Modified');
        } catch (Exception $e) {
            $date = null;
        }

        if (!empty($date)) {
            return strtotime($date);
        }

        try {
            $date = $response->getHeaderLine('Date');
        } catch (Exception $e) {
            $date = null;
        }

        return empty($date) ? time() : strtotime($date);
    }
}
