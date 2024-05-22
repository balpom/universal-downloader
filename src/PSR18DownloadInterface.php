<?php

declare(strict_types=1);

namespace Balpom\UniversalDownloader;

use Psr\Http\Client\ClientInterface;

interface PSR18DownloadInterface extends HttpDownloadInterface, ClientInterface
{

}
