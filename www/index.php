<?php
require_once __DIR__ . '/../vendor/autoload.php';

use RestProxy\RestProxy;

$proxy = new RestProxy();

$proxy->register('github/example/With/2/destinations', 'https://api.github.com');
$proxy->register('github', 'https://api.github.com');
$proxy->run();

foreach ($proxy->getHeaders() as $header) {
    header($header);
}
echo $proxy->getContent();