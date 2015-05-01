<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

use RestProxy\RestProxy;
use RestProxy\CurlWrapper;

// Example for additional Curl request headers and additional curl options for all requests
$requestHeaders = [
    'Content-Type:application/json',
    'Authorization: Basic ' . base64_encode("username:password")
];
$curlOptions    = [
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0
];
$proxy          = new RestProxy(
    Request::createFromGlobals(),
    new CurlWrapper($requestHeaders, $curlOptions)
);

$proxy->register('github/example/With/2/destinations', 'https://api.github.com');
$proxy->register('github', 'https://api.github.com');
$proxy->run();

foreach ($proxy->getHeaders() as $header) {
    header($header);
}
echo $proxy->getContent();
