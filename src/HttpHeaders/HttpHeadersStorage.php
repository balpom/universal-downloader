<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader\HttpHeaders;

class HttpHeadersStorage implements HttpHeadersStorageInterface
{
    private const LINE_SEPARATOR = "\r\n";
    private const VALUE_SEPARATOR = ":";
    private const FULL_VALUE_SEPARATOR = ": ";

    private array $headers;
    private array $headersKeys;

    public function __construct(string|array|null $headers = null)
    {
        $this->headers = [];
        $this->headersKeys = [];
        $this->store($headers);
    }

    public function store(string|array|null $headers = null): void
    {
        if (empty($headers)) {
            return;
        }
        if (is_string($headers)) {
            if (false !== strpos($headers, self::LINE_SEPARATOR)) {
                $headers = explode(self::LINE_SEPARATOR, $headers);
            } else {
                $headers = [$headers];
            }
        }

        // Array with HTTP headers may be in two formats:
        //
        // as line, how on this sample:
        // $headers[0] = 'User-Agent: My Own User Agent';
        // $headers[1] = 'Content-Type: text/html; charset=utf-8';
        // $headers[2] = 'Set-Cookie: username=John';
        // $headers[3] = 'Set-Cookie: uservalue=abrakadabra';
        //
        // as "key-value" array, how on this sample:
        // $headers['User-Agent'] = 'My Own User Agent';
        // $headers['Content-Type'] = 'text/html; charset=utf-8';
        //
        // Moreover, "X-Header" and "x-header" — the same header names.
        foreach ($headers as $key => $value) {
            if (is_integer($key)) {
                $pos = strpos($value, self::VALUE_SEPARATOR);
                if (false !== $pos) {
                    $header = substr($value, 0, $pos);
                    $value = substr($value, $pos + 1);
                    $this->add(trim($header), trim($value));
                } else {
                    $this->add($value, '');
                }
            } else {
                $this->add($key, $value);
            }
        }
    }

    public function getValue(string $header): string|array|null
    {
        $str = $this->get($header);
        $pos = strpos($str, self::LINE_SEPARATOR);

        if (false === $pos) {
            return $this->getValueFromHeaderLine($str);
        }

        $lines = explode(self::LINE_SEPARATOR, $str);
        $values = [];
        foreach ($lines as $line) {
            $value = $this->getValueFromHeaderLine($line);
            if (!empty($value) || '0' === $value || 0 === $value) {
                $values[] = $value;
            }
        }

        if (empty($values)) {
            $values = null;
        }

        return $values;
    }

    public function get(string|null $header = null): string
    {
        $headers = [];
        if (!empty($header)) {
            $this->isExist($header);
            $headerKey = strtolower($header);
        } else {
            $headerKey = false;
        }
        foreach ($this->headers as $currentHeaderKey => $values) {
            if ($headerKey && $headerKey !== $currentHeaderKey) {
                continue;
            }
            foreach ($values as $number => $value) {
                $headerLine = $this->headersKeys[$currentHeaderKey][$number];
                if ('0' !== $value && 0 !== $value && empty($value)) {
                    $headerLine .= self::VALUE_SEPARATOR;
                } else {
                    $headerLine .= self::FULL_VALUE_SEPARATOR . $value;
                }
                $headers[] = $headerLine;
            }
        }

        return implode(self::LINE_SEPARATOR, $headers);
    }

    public function has(string $header): bool
    {
        $this->checkHeader($header);
        $headerKey = strtolower($header);

        return isset($this->headersKeys[$headerKey]);
    }

    public function add(string $header, string|int|float|null $value): void
    {
        $headerKey = strtolower($header);
        if (!$this->has($header)) {
            $this->headersKeys[$headerKey] = [];
            $this->headers[$headerKey] = [];
        }
        $this->headersKeys[$headerKey][] = $header;
        $this->headers[$headerKey][] = (string) $value;
    }

    public function del(string $header): void
    {
        $this->isExist($header);
        $headerKey = strtolower($header);
        unset($this->headersKeys[$headerKey]);
        unset($this->headers[$headerKey]);
    }

    protected function getValueFromHeaderLine(string $header): string|null
    {
        $pos = strpos($header, self::VALUE_SEPARATOR);
        $value = (false === $pos) ? null : trim(substr($header, $pos + 1));

        return $value;
    }

    protected function checkHeader(string $header): void
    {
        if (empty($header)) {
            throw new HttpHeadersException('Empty HTTP header.');
        }
    }

    protected function isExist(string $header): void
    {
        if (!$this->has($header)) {
            throw new HttpHeadersException('HTTP header "' . $header . '" not exist.');
        }
    }

}