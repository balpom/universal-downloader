<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

use Balpom\UniversalDownloader\SimpleDownloader;

$downloader = new SimpleDownloader();

$downloader = $downloader->get('https://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL;

$downloader = $downloader->get('https://ipmy.ru/host');
echo $downloader->content() . PHP_EOL;
