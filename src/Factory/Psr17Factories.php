<?php

namespace Balpom\UniversalDownloader\Factory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class Psr17Factories implements Psr17FactoriesInterface
{

    private RequestFactoryInterface $request;
    private StreamFactoryInterface $stream;
    private UriFactoryInterface $uri;

    public function __construct(
            RequestFactoryInterface $request,
            StreamFactoryInterface $stream,
            UriFactoryInterface $uri)
    {
        $this->request = $request;
        $this->stream = $stream;
        $this->uri = $uri;
    }

    public function request(): RequestFactoryInterface
    {
        return $this->request;
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
