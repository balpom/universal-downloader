# universal-downloader
## Simple interface for content downloading on the specified URI (or file location) and it's trivial realisation, based on PSR18 HTTP clients.

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

### Usage sample
This downloader requires objects that implement the ResponseFactoryInterface, StreamFactoryInterface and UriFactoryInterface interfaces which defined in the [PSR-17 specification](https://www.php-fig.org/psr/psr-17/).
An excellent library that implements all these interfaces at once (all-in-one) is [Nyholm/psr7](https://github.com/Nyholm/psr7) - will use it.

This downloader realisation also requires an HTTP client that implements the ClientInterface which defined in the [PSR-18 specification](https://www.php-fig.org/psr/psr-18/). For example, will use [phpwebclient/webclient](https://github.com/phpwebclient/webclient) and [guzzle/guzzle](https://github.com/guzzle/guzzle).

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
$factories = new \Balpom\Downloader\Factory\Psr17Factories($factory, $factory, $factory);
$downloader = new \Balpom\Downloader\Psr18Downloader($client, $factoryies);
```

#### Downloader creation based on GuzzleHttp.
```php
$client = new \GuzzleHttp\Client();
$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$factories = new \Balpom\Downloader\Factory\Psr17Factories($factory, $factory, $factory);
// In my realisation Psr17Factory factories required.
// You may make your own realisation, bases on GuzzleHttp options (it has own Psr17Factory).
$downloader = new \Balpom\Downloader\Psr18Downloader($client, $factories);
```

#### Download URI
For test purpose will make request to site [https://ipmy.ru](https://ipmy.ru).
```php
$downloader = $downloader->get('http://ipmy.ru/ip');
echo $downloader->code(); echo PHP_EOL; // Must be 200.
echo $downloader->content(); echo PHP_EOL; // Must be your IP.
```

Extended sample you may find in "tests/test.php" file - just run it:
```bash
php tests/test.php
```

### License
MIT License See [LICENSE.MD](LICENSE.MD)
