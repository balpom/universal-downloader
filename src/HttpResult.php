<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

class HttpResult extends Result implements HttpDownloadResultInterface
{

    private string|false $content;
    private int|false $date;
    private string|false $mime;
    private int|false $code;

    public function __construct(string|false $content = false,
            int|false $date = false,
            string|false $mime = false,
            int|false $code = false)
    {
        $this->setContent($content);
        $this->setDate($date);
        $this->setMime($mime);
        $this->setCode($code);
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

    protected function setDate(int|false $date)
    {
        $this->date = $date;
    }

    protected function setMime(string|false $mime)
    {
        if (false !== $mime && !$this->checkMime($mime)) {
            throw new DownloaderException('Invalid MIME!');
        }
        $this->mime = $mime;
    }

    protected function setCode(int|false $code)
    {
        if (false !== $code && !$this->checkCode($code)) {
            throw new DownloaderException('Invalid HTTP code!');
        }
        $this->code = $code;
    }

    protected function checkMime(string $mime)
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

    protected function checkCode(int $code)
    {
        if (100 > $code || 599 < $code) {
            return false;
        }

        return true;
    }
}
