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
echo $downloader->code() . PHP_EOL;
echo $downloader->content() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;

$downloader = $downloader->get('https://ipmy.ru/host');
$html = $downloader->content();
echo $html . PHP_EOL;

sleep(3);

$downloader = new Downloader($client2, $factories);

$downloader = $downloader->get('https://php.net/');
print_r($downloader->response());

