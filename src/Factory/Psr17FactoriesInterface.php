<?php

namespace Balpom\UniversalDownloader\Factory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

interface Psr17FactoriesInterface
{

    public function request(): RequestFactoryInterface;

    public function stream(): StreamFactoryInterface;

    public function uri(): UriFactoryInterface;
}
