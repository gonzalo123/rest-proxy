<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

use RestProxy\RestProxy;
use RestProxy\CurlWrapper;

$proxy = new RestProxy(
    Request::createFromGlobals(),
    new CurlWrapper()
    );
$proxy->register('github/example/With/2/destinations', 'https://api.github.com');
$proxy->register('github', 'https://api.github.com');
$proxy->run();


foreach($proxy->getHeaders() as $header) {
    header($header);
}
echo $proxy->getContent();
