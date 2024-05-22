<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;
use \Exception;

abstract class AbstractPSR18Downloader extends AbstractHttpDownloader implements ClientInterface, PSR18DownloadInterface
{

    protected ClientInterface $client; // PSR18 HTTP client.
    protected ResponseInterface|null $response; // Last response.
    protected int $redirects = 5; // Number of redirects following.
    protected array $headers = [];

    public function result(): HttpDownloadResultInterface
    {
        return new PSR18Result($this->response);
    }

    public function response(): ResponseInterface
    {
        return $this->response;
    }

    abstract public function get(string $uri): DownloadInterface;

    abstract public function head(string $uri): HttpDownloadInterface;

    abstract public function post(string $uri, array $data = []): HttpDownloadInterface;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->response = $this->client->sendRequest($request);

        return $this->response;
    }

    public function withHeader(string $header): PSR18DownloadInterface
    {
        if (!empty($header) && false !== strpos($header, ':')) {
            $this->headers[$header] = $header;
        }

        return $this;
    }

    public function redirects(int $redirects): PSR18DownloadInterface
    {
        if (0 > $redirects) {
            throw new DownloaderException("Number of request attempts must be positive.");
        }
        if (10 < $redirects) {
            throw new DownloaderException("Number of request attempts must be less than or equal 10.");
        }
        $this->redirects = $redirects;

        return $this;
    }

    protected function getLocation(): string|false
    {
        try {
            $location = $this->response->getHeader('Location');
        } catch (Exception $e) {
            throw new DownloaderException("Error: unable to get redirect location.");
        }

        return isset($location[0]) ? $location[0] : false;
    }

    protected function getHeaderName(string $header): string
    {
        $pos = strpos($header, ':');

        return trim(substr($header, 0, $pos));
    }

    protected function getHeaderValue(string $header): string
    {
        $pos = strpos($header, ':') + 1;

        return trim(substr($header, $pos));
    }
}
