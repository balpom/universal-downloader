<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\Result;

use Psr\Http\Message\ResponseInterface;
use \Throwable;

class PSR18Result extends HttpResult implements PSR18DownloadResultInterface
{
    private ResponseInterface|null $response;

    public function __construct(ResponseInterface|null $response = null)
    {
        if (null !== $response) {
            try {
                $responseHeaders = $response->getHeaders();
                if (empty($responseHeaders)) {
                    $response = null;
                }
            } catch (Throwable $e) {
                $response = null;
            }
        }
        $this->response = $response;

        if (null !== $response) {
            $content = $this->getContent();
            $this->setContent($content);
            $date = $this->getDate();
            $this->setDate($date);
            $mime = $this->getMime();
            $this->setMime($mime);
            $code = $this->getCode();
            $this->setCode($code);
            $charset = $this->getCharset();
            $this->setCharset($charset);
        } else {
            $this->setContent(false);
            $this->setDate(false);
            $this->setMime(false);
            $this->setCode(false);
            $this->setCharset(false);
        }
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
        if (null === $response) {
            return false;
        }

        $code = (string) $this->getCode();
        if ('2' !== substr($code, 0, 1)) {
            return false;
        }

        try {
            $content = $response->getBody()->getContents();
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
            return false;
        }

        if (empty($mime)) {
            return false;
        }

        $mime = strtolower($mime);
        $pos = strpos($mime, ':');
        if (false !== $pos) {
            $mime = substr($mime, $pos + 1);
        }
        $pos = strpos($mime, ';');
        if (false !== $pos) {
            $mime = substr($mime, 0, $pos);
        }

        $mime = trim($mime);

        return empty($mime) ? false : $mime;
    }

    private function getCharset(): string|false
    {
        $response = $this->response();
        if (null === $response) {
            return false;
        }

        try {
            $charset = $response->getHeaderLine('Content-Type');
        } catch (Throwable $e) {
            return false;
        }

        if (empty($charset)) {
            return false;
        }

        $pos = strpos($charset, ':');
        if (false !== $pos) {
            $charset = substr($charset, $pos + 1);
        }
        $pos = strpos($charset, ';');
        if (false !== $pos) {
            $charset = substr($charset, $pos + 1);
            $pos = strpos($charset, '=');
            if (false !== $pos) {
                $charset = substr($charset, $pos + 1);
            } else {
                $charset = '';
            }
        }

        $charset = trim($charset);

        return empty($charset) ? false : $charset;
    }

    private function getDate(): int|false
    {
        $response = $this->response();
        if (null === $response) {
            return false;
        }

        try {
            $date = $response->getHeaderLine('Last-Modified');
        } catch (Throwable $e) {
            $date = null;
        }

        if (!empty($date)) {
            return strtotime($date);
        }

        try {
            $date = $response->getHeaderLine('Date');
        } catch (Throwable $e) {
            $date = null;
        }

        return empty($date) ? time() : strtotime($date);
    }

}