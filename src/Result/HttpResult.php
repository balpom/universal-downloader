<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\Result;

class HttpResult extends Result implements HttpDownloadResultInterface
{
    private string|false $content;
    private int|false $date;
    private string|false $mime;
    private int|false $code;
    private array|false $headers;
    private string|false $charset;

    public function __construct(
            string|false $content = false,
            int|false $date = false,
            string|false $mime = false,
            int|false $code = false,
            array|false $headers = false,
            string|false $charset = false
    )
    {
        $this->setContent($content);
        $this->setDate($date);
        $this->setMime($mime);
        $this->setCode($code);
        $this->setHeaders($headers);
        $this->setCharset($charset);
    }

    public function date(): int|false
    {
        return $this->date;
    }

    public function mime(): string|false
    {
        return $this->mime;
    }

    public function code(): int|false
    {
        return $this->code;
    }

    public function headers(): array|false
    {
        return $this->headers;
    }

    public function charset(): string|false
    {
        return $this->charset;
    }

    protected function setDate(int|false $date): void
    {
        $this->date = $date;
    }

    protected function setMime(string|false $mime): void
    {
        if (false !== $mime && !$this->checkMime($mime)) {
            throw new DownloaderException('Invalid MIME!');
        }
        $this->mime = $mime;
    }

    protected function setCode(int|false $code): void
    {
        if (false !== $code && !$this->checkCode($code)) {
            throw new DownloaderException('Invalid HTTP code!');
        }
        $this->code = $code;
    }

    protected function setHeaders(array|false $headers): void
    {
        if (false !== $headers && !$this->checkHeaders($headers)) {
            throw new DownloaderException('Invalid HTTP headers!');
        }
        $this->headers = $headers;
    }

    protected function setCharset(string|false $charset): void
    {
        if (false !== $charset && 1 > strlen($charset)) {
            throw new DownloaderException('Invalid charset!');
        }
        $this->charset = $charset;
    }

    protected function checkMime(string $mime): bool
    {
        if (false === strpos($mime, '/') || 1 < substr_count($mime, '/')) {
            return false;
        }
        $mime = explode('/', $mime);
        if (1 > strlen($mime[0]) || 1 > strlen($mime[1])) {
            return false;
        }

        return true;
    }

    protected function checkCode(int $code): bool
    {
        if (100 > $code || 599 < $code) { // Simple checking... TODO: full checking.
            return false;
        }

        return true;
    }

    protected function checkHeaders(array $headers): bool
    {
        if (empty($headers)) {
            return true;
        }

        foreach ($headers as $key => $value) {
            if ('0' !== $value && 0 !== $value && empty($value)) {
                return false;
            }
            if (is_integer($key)) {
                if (!is_string($value)) {
                    return false;
                }
            } else {
                $type = gettype($value);
                if (!in_array($type, ['string', 'integer', 'double', 'NULL'])) {
                    return false;
                }
            }
        }

        return true;
    }

}