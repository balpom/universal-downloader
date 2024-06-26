<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\Factory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class Psr17Factories implements Psr17FactoriesInterface
{

    private RequestFactoryInterface $request;
    private ResponseFactoryInterface $response;
    private StreamFactoryInterface $stream;
    private UriFactoryInterface $uri;

    public function __construct(
            RequestFactoryInterface $request,
            ResponseFactoryInterface $response,
            StreamFactoryInterface $stream,
            UriFactoryInterface $uri)
    {
        $this->request = $request;
        $this->response = $response;
        $this->stream = $stream;
        $this->uri = $uri;
    }

    public function request(): RequestFactoryInterface
    {
        return $this->request;
    }

    public function response(): ResponseFactoryInterface
    {
        return $this->response;
    }

    public function stream(): StreamFactoryInterface
    {
        return $this->stream;
    }

    public function uri(): UriFactoryInterface
    {
        return $this->uri;
    }
}
