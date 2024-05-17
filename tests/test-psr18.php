<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

use Balpom\UniversalDownloader\Factory\Psr17Factories;
use Balpom\UniversalDownloader\Downloader;
use Nyholm\Psr7\Factory\Psr17Factory;
use Webclient\Http\Webclient;
use GuzzleHttp\Client;

$factory = new Psr17Factory();
$factories = new Psr17Factories($factory, $factory, $factory);
$client1 = new Webclient($factory, $factory);
$client2 = new Client();

$downloader = new Downloader($client1, $factories);

$downloader = $downloader->get('https://ipmy.ru/ip');
$result = $downloader->result();
echo $result->code() . PHP_EOL;
echo $result->content() . PHP_EOL;
echo $result->mime() . PHP_EOL;

$downloader = $downloader->get('https://ipmy.ru/host');
$result = $downloader->result();
$html = $result->content();
echo $html . PHP_EOL;

sleep(3);

$downloader = new Downloader($client2, $factories);

$result = $downloader->get('https://php.net/');
print_r($result->response());

