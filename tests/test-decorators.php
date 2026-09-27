<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

use Balpom\UniversalDownloader\Factory\Psr17Factories;
use Nyholm\Psr7\Factory\Psr17Factory;
use GuzzleHttp\Client;

$downloader = new HttpDownloaderResultDecorator(new SimpleHttpDownloader());

$downloader->get('http://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL;
echo $downloader->date() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;
echo $downloader->charset() . PHP_EOL;

echo '------------------------------------------' . PHP_EOL;

$factory = new Psr17Factory();
$factories = new Psr17Factories($factory, $factory, $factory, $factory);
$client = new Client();

$downloader = new PSR18DownloaderResultDecorator(new Downloader($client, $factories));
$downloader->get('http://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL;
echo $downloader->date() . PHP_EOL;
echo $downloader->code() . PHP_EOL;
echo $downloader->mime() . PHP_EOL;
echo $downloader->charset() . PHP_EOL;
