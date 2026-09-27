<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Balpom\UniversalDownloader\HttpHeaders\HttpHeadersStorage;
use Balpom\UniversalDownloader\Result\DownloadResultInterface;
use \Throwable;

class SimpleDownloader extends AbstractDownloader implements DownloadInterface, DownloadResultInterface
{
    protected array $options = [];
    protected string|false|null $content = false;
    private array|null $responseHeadersArray = null;

    public function __construct(bool $followLocation = false, bool $verifyPeer = false, bool $verifyPeerName = false)
    {
        $this->options = [
            'http' => [
                'follow_location' => $followLocation ? 1 : 0, // 0 - don't follow redirects.
            ],
            'ssl' => [
                'verify_peer' => $verifyPeer, // FALSE - disable certificate verification.
                'verify_peer_name' => $verifyPeerName, // FALSE - disable hostname (domain) verification.
            ]
        ];
    }

    public function get(string $uri, string|array|null $headers = null): DownloadInterface
    {
        $headers = new HttpHeadersStorage($headers);
        $this->options['http']['method'] = 'GET';
        $this->options['http']['header'] = $headers->get();
        $this->fileGetContents($uri);

        return $this;
    }

    public function fileGetContents(string $uri): void
    {
        $this->content = false;
        $this->options['http']['timeout'] = $this->timeout;
        $context = stream_context_create($this->options);
        $attempt = 0;
        do {
            $attempt++;
            try {
                $content = @file_get_contents($uri, false, $context);
                // For PHP >= 8.4.0 $http_response_header is deprecated.
                if (function_exists('http_get_last_response_headers')) {
                    try {
                        $this->responseHeadersArray = http_get_last_response_headers();
                    } catch (Throwable $e) {
                        $this->responseHeadersArray = null;
                    }
                } else {
                    if (isset($http_response_header)) {
                        $this->responseHeadersArray = $http_response_header;
                    } else {
                        $this->responseHeadersArray = null;
                    }
                }
            } catch (Throwable $e) { // Not doing anything.
            }
            if (false !== $content) {
                $this->content = $content;
                break;
            }
        } while ($attempt <= $this->attempts);
    }

    public function content(): string|false
    {
        return (null !== $this->content) ? $this->content : false;
    }

    protected function responseHeaders(): array|null
    {
        return $this->responseHeadersArray;
    }

}