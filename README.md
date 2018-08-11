# Bulldog Router

[![Build Status](https://travis-ci.org/bulldogcreative/bulldog.router.svg?branch=master)](https://travis-ci.org/bulldogcreative/bulldog.router)
[![Coverage Status](https://coveralls.io/repos/github/bulldogcreative/bulldog.router/badge.svg?branch=master)](https://coveralls.io/github/bulldogcreative/bulldog.router?branch=master)

A simple PHP router that utilizes [nikic/FastRoute][1].

## Installation

```sh
composer require bulldog/router
```

## Usage

```php
<?php

use Bulldog\Router;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

// You'll usually use the line below, but to demonstrate, we will create our own request.
// $request = ServerRequestFactory::fromGlobals();
$request = new ServerRequest([], [], '/', 'GET');

$router = new Router;
$router->addRoute('get', '/', 'callable');
$router->run($request);

echo $router->handler();
// callable

var_dump($router->vars());
// array(0) {
// }
```

### With Array Parameters

```php
<?php

use Bulldog\Router;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

// You'll usually use the line below, but to demonstrate, we will create our own request.
// $request = ServerRequestFactory::fromGlobals();
$request = new ServerRequest([], [], '/user/1', 'GET');

$router = new Router;
$router->addRoute('get', '/user/{id}', 'callable');
$router->run($request);

echo $router->handler();
// callable

var_dump($router->vars());
// array(1) {
//   'id' =>
//   string(1) "1"
// }
```

## Contributing

All contributions welcome! Please first create an issue if something is wrong
and let us know if you intend to fix it. Then fork the repo, create a new
branch, and work on the issue. The branch name should be relevant to the
issue.

## Style

Run `php-cs-fixer` with the default rules.

```bash
php-cs-fixer fix ./src
```

[1]: https://github.com/nikic/FastRoute