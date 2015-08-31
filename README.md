# log-http-messages middleware [![Build Status](https://travis-ci.org/php-middleware/log-http-messages.svg)](https://travis-ci.org/php-middleware/log-http-messages)
Request and response middleware logger with PSR-7 and PSR-3

This middleware provide framework-agnostic possibility to log request and response messages to PSR-3 logger.

## Installation

```json
{
    "require": {
        "php-middleware/log-http-messages": "^1.0.0"
    }
}
```

To log any request you need pass into `LogRequestMiddleware` instance `Psr\Log\LoggerInterface` and add middleware to your middleware runner. To log response use `LogResponseMiddleware`.

```php
$logRequestMiddleware = new PhpMiddleware\LogHttpMessages\LogRequestMiddleware($logger);
$logResponseMiddleware = new PhpMiddleware\LogHttpMessages\LogResponseMiddleware($logger);

$app = new MiddlewareRunner();
$app->add($logRequestMiddleware);
$app->add($logResponseMiddleware);
$app->run($request, $response);
```

Middlewares have optional second parameter in constructor with log level (default `Psr\Log\LogLevel::INFO`).

## It's just works with any modern php framework and logger!

Middleware tested on:
* [Expressive](https://github.com/zendframework/zend-expressive)
* [monolog](https://github.com/Seldaek/monolog)

Middleware should works with:
* [Slim 3.x](https://github.com/slimphp/Slim)
* [zend-log 2.6](https://github.com/zendframework/zend-log)

And any other modern framework [supported middlewares and PSR-7](https://mwop.net/blog/2015-01-08-on-http-middleware-and-psr-7.html) and [PSR-3 implementation](http://www.php-fig.org/psr/psr-3/) logger.
