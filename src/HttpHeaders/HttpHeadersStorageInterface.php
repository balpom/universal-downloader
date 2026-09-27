<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\HttpHeaders;

interface HttpHeadersStorageInterface
{

    public function getValue(string $header): string|array|null;

    public function get(string|null $header = null): string;

    public function has(string $header): bool;

    public function add(string $header, string|int|float|null $value): void;

    public function del(string $header): void;

}