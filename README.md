# Borsch - Middlewares

A collection of common PSR-15 Middleware for Borsch applications.

This package is part of the Borsch Framework.

## Installation

Via [composer](https://getcomposer.org/) :

`composer require borsch/middlewares`

## Usage

```php
require_once __DIR__.'/vendor/autoload.php';

use Borsch\RequestHandler\RequestHandler;
use Borsch\Middleware\ErrorHandlerMiddleware;
use Borsch\Middleware\RouteMiddleware;
use Borsch\Middleware\ImplicitHeadMiddleware;
use Borsch\Middleware\ImplicitOptionsMiddleware;
use Borsch\Middleware\MethodNotAllowedMiddleware;
use \Borsch\Middleware\DispatchMiddleware;
use \Borsch\Middleware\NotFoundHandlerMiddleware;

$request_handler = new RequestHandler();

$request_handler->middlewares([
    ErrorHandlerMiddleware::class,
    RouteMiddleware::class,
    ImplicitHeadMiddleware::class,
    ImplicitOptionsMiddleware::class,
    MethodNotAllowedMiddleware::class,
    DispatchMiddleware::class,
    NotFoundHandlerMiddleware::class
]);

$response = $request_handler->handle(ServerRequestFactory::fromGlobals());
```

## License

The package is licensed under the MIT license. See [License File](https://github.com/debuss/borsch-middlewares/blob/master/LICENSE.md) for more information.