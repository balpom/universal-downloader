<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

$downloader = new SimpleHttpDownloader();

$downloader->get('http://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL;
echo $downloader->date() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;
echo $downloader->charset() . PHP_EOL;

echo '------------------------------------------' . PHP_EOL;

$downloader->post('https://httpbin.org/post', 'name=John Doe&password=hz');
echo $downloader->content() . PHP_EOL;
echo $downloader->date() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;
echo $downloader->charset() . PHP_EOL;

$downloader->post('https://httpbin.org/post', ['name' => 'John Doe', 'password' => 'hz']);
echo $downloader->content() . PHP_EOL;

echo '------------------------------------------' . PHP_EOL;

$downloader->put('https://httpbin.org/put', ['name' => 'John Doe', 'password' => 'hz']);
echo $downloader->content() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;
echo PHP_EOL;

$downloader->patch('https://httpbin.org/patch', ['name' => 'John Doe', 'password' => 'hz']);
echo $downloader->content() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;

echo '------------------------------------------' . PHP_EOL;

$downloader->delete('https://httpbin.org/delete');
echo $downloader->content() . PHP_EOL;
echo PHP_EOL;

$downloader->get('https://httpbin.org/get');
echo $downloader->content() . PHP_EOL;
echo PHP_EOL;

$downloader->head('https://httpbin.org/get'); // HEAD method returns empty content.
echo $downloader->date() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;

echo '------------------------------------------' . PHP_EOL;

$downloader->post('https://httpbin.org/post', ['name' => 'John Doe', 'password' => 'hz'], 'Cookies: uservalue=abrakadabra');
echo $downloader->content() . PHP_EOL;

$downloader->post('https://httpbin.org/post', null, 'My-Empty-Header:' . "\r\n" . 'My-Header:hz');
echo $downloader->content() . PHP_EOL;

$downloader->post('https://httpbin.org/post', 'name=John+Doe&password=hz', ['My-Empty-Header' => null, 'My-Header' => 'hz']);
echo $downloader->content() . PHP_EOL;
