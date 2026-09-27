<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

$downloader = new SimpleDownloader();

$downloader->get('http://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL;

$downloader->get('http://ipmy.ru/host');
echo $downloader->content() . PHP_EOL;

$downloader->get('https://httpbin.org/anything');
echo $downloader->content() . PHP_EOL;
