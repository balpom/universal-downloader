<?php

namespace Balpom\UniversalDownloader\HttpHeaders;

require __DIR__ . "/../vendor/autoload.php";

$storage = new HttpHeadersStorage();

$header1 = 'Accept-Language';
$value1 = 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7';

$header2 = 'Cache-Control';
$value2 = 'no-cache';

$header3 = 'User-Agent';
$value3 = 'My Own User Agent';

echo ($storage->has($header1) ? 'TRUE' : 'FALSE') . PHP_EOL; // FALSE
$storage->add($header1, $value1);
echo ($storage->has($header1) ? 'TRUE' : 'FALSE') . PHP_EOL; // TRUE
echo $storage->get($header1) . PHP_EOL;
$storage->del($header1);
echo ($storage->has($header1) ? 'TRUE' : 'FALSE') . PHP_EOL; // FALSE

$headers = [$header1 => $value1, $header2 => $value2, $header3 => $value3];
$storage->store($headers);
echo $storage->get($header1) . PHP_EOL;
echo $storage->get($header2) . PHP_EOL;
echo $storage->get($header3) . PHP_EOL;

echo '-----------------------------------------------------' . PHP_EOL;

$headers = [];
$headers = ['Set-Cookie: username=John',
    'Set-Cookie: uservalue1=abrakadabra',
    'sET-cOOKIE: uservalue2=kadabraabra'];
$storage = new HttpHeadersStorage($headers);
echo $storage->get() . PHP_EOL;

echo '-----------------------------------------------------' . PHP_EOL;

$headers = 'Set-Cookie: uservalue=abrakadabra';
$storage = new HttpHeadersStorage($headers);
echo $storage->get() . PHP_EOL;
