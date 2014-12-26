rest-proxy [![Build Status](https://secure.travis-ci.org/gonzalo123/rest-proxy.png?branch=master)](http://travis-ci.org/gonzalo123/rest-proxy)
=========================

Simple Rest Proxy

Example
=========================

```
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
```

How to install:
=========================
Install composer:
```
curl -s https://getcomposer.org/installer | php
```

Create a new project:

```
php composer.phar create-project gonzalo123/rest-proxy proxy
```

Run dummy server (only with PHP5.4)

```
cd proxy
php -S localhost:8888 -t www/
```

Open a web browser and type: http://localhost:8888/github/users/gonzalo123

