# balpom/universal-downloader
## Simple interfaces for content downloading on the specified URI (or file location) and it's trivial realisations.

This downloader will be useful for websites parsing, working with the REST API and other work with WEB resources via the HTTP protocol.
This version of the package contains an interface implementation for use with any PSR-18 HTTP clients.
It is planned to make an implementation that works through [Selenium WebDriver](https://github.com/php-webdriver/php-webdriver).

### Requirements 
- **PHP >= 8.1**

### Installation
#### Using composer (recommended)
```bash
composer require balpom/universal-downloader
```

### Simple downloader usage sample

```php
$downloader = new \Balpom\UniversalDownloader\SimpleDownloader();
$downloader = $downloader->get('https://ipmy.ru/ip');
echo $downloader->content() . PHP_EOL; // Must be your IP.
```

### PSR18 downloader usage sample
PSR18 downloader requires objects that implement the ResponseFactoryInterface, StreamFactoryInterface and UriFactoryInterface interfaces which defined in the [PSR-17 specification](https://www.php-fig.org/psr/psr-17/).
An excellent library that implements all these interfaces at once (all-in-one) is [Nyholm/psr7](https://github.com/Nyholm/psr7) - will use it.

PSR18 downloader realisation also requires an HTTP client that implements the ClientInterface which defined in the [PSR-18 specification](https://www.php-fig.org/psr/psr-18/). For example, will use [phpwebclient/webclient](https://github.com/phpwebclient/webclient) and [guzzle/guzzle](https://github.com/guzzle/guzzle).

#### Installing third-party packages
```bash
composer require nyholm/psr7
```
```bash
composer require webclient/webclient
```
```bash
composer require guzzlehttp/guzzle
```

#### Downloader creation based on Webclient.
```php
$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Webclient\Http\Webclient($factory, $factory);
// Psr17Factories(RequestFactoryInterface $request, StreamFactoryInterface $stream, UriFactoryInterface $uri)
$factories = new \Balpom\UniversalDownloader\Factory\Psr17Factories($factory, $factory, $factory, $factory);
$downloader = new \Balpom\UniversalDownloader\Downloader($client, $factories);
```

#### Downloader creation based on GuzzleHttp.
```php
$client = new \GuzzleHttp\Client();
$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$factories = new \Balpom\Downloader\Factory\Psr17Factories($factory, $factory, $factory, $factory);
// In my realisation Psr17Factory factories required.
// You may make your own realisation, bases on GuzzleHttp options (it has own Psr17Factory).
$downloader = new \Balpom\UniversalDownloader\Downloader($client, $factories);
```

#### Download URI
For test purpose will make request to site [https://ipmy.ru](https://ipmy.ru).
```php
$downloader = $downloader->get('http://ipmy.ru/ip');
$result = $downloader->result();
echo $result->code(); echo PHP_EOL; // Must be 200.
echo $result->content(); echo PHP_EOL; // Must be your IP.
echo $result->mime(); echo PHP_EOL; // Must be "text/html".
```

Extended sample you may find in "tests/test-psr18.php" file - just run it:
```bash
php tests/test-psr18.php
```

### License
MIT License See [LICENSE.MD](LICENSE.MD)
