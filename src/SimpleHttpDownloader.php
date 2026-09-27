<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Balpom\UniversalDownloader\HttpHeaders\HttpHeadersStorage;
use Balpom\UniversalDownloader\Result\HttpDownloadResultInterface;
use Balpom\UniversalDownloader\Result\HttpResult;

class SimpleHttpDownloader extends SimpleDownloader implements HttpDownloadInterface, HttpDownloadResultInterface
{
    protected array $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];
    protected HttpHeadersStorage $responseHeaders;
    private HttpDownloadResultInterface $result;

    public function __construct()
    {
        parent::__construct();
        $this->result = new HttpResult();
    }

    public function get(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('GET', $uri, null, $headers);
    }

    public function head(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('HEAD', $uri, null, $headers);
    }

    public function post(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('POST', $uri, $body, $headers);
    }

    public function put(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('PUT', $uri, $body, $headers);
    }

    public function patch(string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('PATCH', $uri, $body, $headers);
    }

    public function delete(string $uri, string|array|null $headers = null): HttpDownloadInterface
    {
        return $this->connect('DELETE', $uri, null, $headers);
    }

    public function result(): HttpDownloadResultInterface
    {
        if ($this->responseHeaders()) {
            $this->result = new HttpResult(
                    $this->content(),
                    $this->date(),
                    $this->mime(),
                    $this->code(),
                    $this->headers(),
                    $this->charset()
            );
        }

        return $this->result;
    }

    public function date(): int|false
    {
        $date = $this->result->date();
        if (false === $date) {
            try {
                $date = $this->responseHeaders->getValue('Date');
            } catch (DownloaderException $e) {
                return false;
            }
            if (empty($date) || is_array($date)) {
                return false;
            }
            $date = strtotime($date);
        }

        return $date;
    }

    public function mime(): string|false
    {
        $mime = $this->result->mime();
        if (false === $mime) {
            try {
                $mime = $this->responseHeaders->getValue('Content-Type');
            } catch (DownloaderException $e) {
                return false;
            }
            if (empty($mime) || is_array($mime)) {
                return false;
            }
            $pos = strpos($mime, ';');
            if (false !== $pos) {
                $mime = substr($mime, 0, $pos);
                $mime = strtolower($mime);
            }
        }

        return $mime;
    }

    public function code(): int|false
    {
        $code = $this->result->code();
        if (false === $code) {
            $headers = $this->headers();
            if (!isset($headers[0])) {
                return false;
            }
            $pos = strpos($headers[0], ' ');
            if (false === $pos) {
                return false;
            }
            $pieces = explode(' ', $headers[0]);
            $code = (integer) $pieces[1];
        }

        return $code;
    }

    public function headers(): array|false
    {
        $headers = $this->result->headers();
        if (false === $headers) {
            $responseHeaders = $this->responseHeaders();
            $headers = empty($responseHeaders) ? false : $responseHeaders;
        }

        return $headers;
    }

    public function charset(): string|false
    {
        $charset = $this->result->charset();
        if (false === $charset) {
            try {
                $charset = $this->responseHeaders->getValue('Content-Type');
            } catch (DownloaderException $e) {
                return false;
            }

            if (empty($charset) || is_array($charset)) {
                return false;
            }

            $pos = strpos($charset, ';');
            if (false !== $pos) {
                $charset = substr($charset, $pos + 1);
                $pos = strpos($charset, '=');
                if (false !== $pos) {
                    $charset = substr($charset, $pos + 1);
                }
                $charset = empty($charset) ? false : $charset;
            } else {
                $charset = false;
            }
        }

        return $charset;
    }

    protected function formatBody(string|array|null $body): string
    {
        if (empty($body)) {
            return '';
        }
        if (is_string($body)) {
            return $body;
        }

        return http_build_query($body);
    }

    protected function connect(string $method, string $uri, string|array|null $body = null, string|array|null $headers = null): HttpDownloadInterface
    {
        if (!in_array($method, $this->methods)) {
            throw new HttpDownloaderException('Unknown method:' . $method);
        }

        $headers = new HttpHeadersStorage($headers);
        $this->options['http']['method'] = $method;
        $body = $this->formatBody($body);

        if ('POST' === $method || 'PUT' === $method || 'PATCH' === $method) {
            if (!$headers->has('Content-Length')) {
                $headers->add('Content-Length', strlen($body));
            }
            $this->options['http']['content'] = $body;
        }

        $this->options['http']['header'] = $headers->get();

        $this->fileGetContents($uri);
        $responseHeaders = $this->responseHeaders();
        $this->responseHeaders = new HttpHeadersStorage($responseHeaders);
        if (!$responseHeaders) {
            $this->result = new HttpResult(); // Empty result.
        }

        $this->clearOptionalOptions();

        return $this;
    }

    protected function clearOptionalOptions(): void
    {
        $optional = ['method', 'content', 'header'];
        foreach ($optional as $option) {
            if (isset($this->options['http'][$option])) {
                unset($this->options['http'][$option]);
            }
        }
    }

}