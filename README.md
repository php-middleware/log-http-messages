# log-http-messages middleware [![Build Status](https://travis-ci.org/php-middleware/log-http-messages.svg)](https://travis-ci.org/php-middleware/log-http-messages)
PSR-15 middleware for log PSR-7 HTTP messages using PSR-3 logger

This middleware provide framework-agnostic possibility to log request and response messages to PSR-3 logger.
Support double and single (PSR-15) pass middleware.

## Installation

```
composer require php-middleware/log-http-messages
```

To log http messages you need pass into `LogRequestMiddleware` implementation of `PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter`,
instance `Psr\Log\LoggerInterface` and add middleware to your middleware runner.
Third parameter is log level and it's optional (`Psr\Log\LogLevel::INFO` as default).

There are tree implementation of `PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter`:

* `PhpMiddleware\LogHttpMessages\Formatter\RequestFormatter` to log request message,
* `PhpMiddleware\LogHttpMessages\Formatter\ResponseFormatter` to log response message,
* `PhpMiddleware\LogHttpMessages\Formatter\BothFormatter` to log request and response message.

```php
$requestFormatter = PhpMiddleware\LogHttpMessages\Formatter\RequestFormatter();
$responseFormatter = PhpMiddleware\LogHttpMessages\Formatter\ResponseFormatter();
$formatter = new PhpMiddleware\LogHttpMessages\Formatter\BothFormatter(requestFormatter, responseFormatter);
$logMiddleware = new PhpMiddleware\LogHttpMessages\LogMiddleware(formatter, $logger);

$app = new MiddlewareRunner();
$app->add($logMiddleware);
$app->run($request, $response);
```

## It's just works with any modern php framework and logger!

Middleware tested on:
* [Expressive](https://github.com/zendframework/zend-expressive)
* [monolog](https://github.com/Seldaek/monolog)

Middleware should works with:
* [Slim 3.x](https://github.com/slimphp/Slim)
* [zend-log 2.6](https://github.com/zendframework/zend-log)

And any other modern framework [supported middlewares and PSR-7](https://mwop.net/blog/2015-01-08-on-http-middleware-and-psr-7.html) and [PSR-3 implementation](http://www.php-fig.org/psr/psr-3/) logger.
