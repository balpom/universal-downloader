<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Balpom\UniversalDownloader\Factory\Psr17FactoriesInterface;
use \Exception;

class Downloader extends AbstractPSR18Downloader
{

    protected ClientInterface $client;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;
    protected UriFactoryInterface $uriFactory;

    public function __construct(
            ClientInterface $client,
            Psr17FactoriesInterface $factory)
    {
        $this->client = $client;
        $this->requestFactory = $factory->request();
        $this->streamFactory = $factory->stream();
        $this->uriFactory = $factory->uri();
    }

    public function get(string $uri): PSR18DownloadInterface
    {
        try {
            $request = $this->requestFactory->createRequest('GET', $uri);
        } catch (Exception $e) {
            throw new DownloadException("Don't create GET request for URI " . $uri);
        }

        return $this->send($request);
    }

    public function head(string $uri): PSR18DownloadInterface
    {
        try {
            $request = $this->requestFactory->createRequest('HEAD', $uri);
        } catch (Exception $e) {
            throw new DownloadException("Don't create HEAD request for URI " . $uri);
        }

        return $this->send($request);
    }

    public function post(string $uri, array $data = []): PSR18DownloadInterface
    {
        try {
            $request = $this->requestFactory->createRequest('POST', $uri);
            $data = http_build_query($data);
            $stream = $this->streamFactory->createStream($data);
            $request = $request->withBody($stream);
        } catch (Exception $e) {
            throw new DownloadException("Don't create POST request for URI " . $uri);
        }

        return $this->send($request);
    }

    protected function send(RequestInterface $request): PSR18DownloadInterface
    {
        $request = $this->addRequiredHeaders($request);
        $attempt = 0;
        $redirects = 0;
        do {
            $attempt++;
            try {
                @$this->response = $this->sendRequest($request);
                @$code = $this->response->getStatusCode();

                // 3xx redirects
                if ((301 === $code || 302 === $code || 303 === $code || 307 === $code) && 0 < $this->redirects && $redirects < $this->redirects) {
                    $redirects++;
                    if ($location = $this->getLocation()) {
                        $uri = $this->uriFactory->createUri($location);
                    }
                    $request = $request->withUri($uri);
                    if (303 === $code) {
                        $request = $request->withMethod('GET');
                    }
                    $attempt = 0;
                    continue;
                }

                // 426 Upgrade Required
                if (426 === $code) {
                    if ($upgrade = $this->response->getHeader('Upgrade')) {
                        $request = $request->withProtocolVersion($upgrade);
                        $attempt = 0;
                        continue;
                    } else {
                        break;
                    }
                }

                // 505 HTTP Version Not Supported
                if (505 === $code) {
                    $request = $request->withProtocolVersion($this->response->getProtocolVersion());
                    $attempt = 0;
                    continue;
                }

                // 429 Too Many Requests
                // 503 Service Unavailable
                if (429 === $code || 503 === $code) {
                    if ($retry = $this->response->getHeader('Retry-After')) {
                        if ($retry > 3600) {
                            $retry = 3600;
                        }
                        if ($this->pause < $retry) {
                            $this->pause = $retry;
                        }
                    } else {
                        break;
                    }
                }

                if (!in_array($code, [
                            408, 409, 421, 423, 424, 429, 499, 500, 502, 503,
                            504, 507, 520, 521, 522, 532, 524, 525, 526
                        ])) {
                    break;
                }

                if ($attempt < $this->attempts) {
                    sleep($this->pause);
                }
            } catch (Exception $e) { // Not doing anything.
            }
        } while ($attempt <= $this->attempts);

        return $this;
    }

    protected function addRequiredHeaders(RequestInterface $request): RequestInterface
    {
        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                $name = $this->getHeaderName($header);
                $value = $this->getHeaderValue($header);
                if (!empty($name) && !empty($value)) {
                    $request = $request->withAddedHeader($name, $value);
                }
            }
        }

        return $request;
    }
}
