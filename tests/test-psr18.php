<?php

namespace Balpom\UniversalDownloader;

require __DIR__ . "/../vendor/autoload.php";

use Balpom\UniversalDownloader\Factory\Psr17Factories;
use Nyholm\Psr7\Factory\Psr17Factory;
use Webclient\Http\Webclient;
use GuzzleHttp\Client;

$factory = new Psr17Factory();
$factories = new Psr17Factories($factory, $factory, $factory, $factory);
$client1 = new Webclient($factory, $factory);
$client2 = new Client();

$downloader = new Downloader($client1, $factories);

$downloader->get('http://ipmy.ru/ip');
$result = $downloader->result();
echo $result->date() . PHP_EOL;
echo $result->code() . PHP_EOL;
echo $result->content() . PHP_EOL;
echo $result->mime() . PHP_EOL;
echo $result->charset() . PHP_EOL;

$downloader = new Downloader($client2, $factories);

$downloader->get('http://ipmy.ru/host');
$result = $downloader->result();
$html = $result->content();
echo $html . PHP_EOL;
echo $result->code() . PHP_EOL;

echo '----- It was IPMY.RU tests.' . PHP_EOL . PHP_EOL;

$downloader->post('https://httpbin.org/post', 'name=John Doe&password=hz');
$result = $downloader->result();
echo $result->content() . PHP_EOL;
echo $result->date() . PHP_EOL;
echo $result->code() . PHP_EOL;
echo $result->mime() . PHP_EOL;
echo $result->charset() . PHP_EOL;

$downloader->post('https://httpbin.org/post', ['name' => 'John Doe', 'password' => 'hz']);
$result = $downloader->result();
echo $result->content() . PHP_EOL;

echo '----- It was HTTPBIN.ORG POST method tests.' . PHP_EOL . PHP_EOL;

$downloader->put('https://httpbin.org/put', ['name' => 'John Doe', 'password' => 'hz']);
$result = $downloader->result();
echo $result->content() . PHP_EOL;
echo $result->code() . PHP_EOL;
echo $result->mime() . PHP_EOL;
echo PHP_EOL;

$downloader = $downloader->patch('https://httpbin.org/patch', ['name' => 'John Doe', 'password' => 'hz']);
$result = $downloader->result();
echo $result->content() . PHP_EOL;
echo $result->code() . PHP_EOL;
echo $result->mime() . PHP_EOL;

echo '----- It was HTTPBIN.ORG PUT and PATCH methods tests.' . PHP_EOL . PHP_EOL;

$downloader->delete('https://httpbin.org/delete');
$result = $downloader->result();
echo $result->content() . PHP_EOL;
echo PHP_EOL;

$downloader->get('https://httpbin.org/get');
$result = $downloader->result();
echo $result->content() . PHP_EOL;
echo PHP_EOL;

$downloader->head('https://httpbin.org/get'); // HEAD method returns empty content.
$result = $downloader->result();
echo $result->date() . PHP_EOL;
echo $result->code() . PHP_EOL;
echo $result->mime() . PHP_EOL;

echo '----- It was HTTPBIN.ORG DELETE, GET and HEAD methods tests.' . PHP_EOL . PHP_EOL;

$result = $downloader->get('http://ipmy.ru/');
$result = $downloader->result();
print_r($result->response());

echo '----- It was response dump from IPMY.RU.' . PHP_EOL;
