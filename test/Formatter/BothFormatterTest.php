<?php

namespace PhpMiddlewareTest\LogHttpMessages\Formatter;

use PhpMiddleware\LogHttpMessages\Formatter\BothFormatter;
use PhpMiddleware\LogHttpMessages\Formatter\RequestFormatter;
use PhpMiddleware\LogHttpMessages\Formatter\ResponseFormatter;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class BothFormatterTest extends PHPUnit_Framework_TestCase
{
    protected $formatter;

    protected function setUp()
    {
        $requestFormatter = new RequestFormatter();
        $responseFormatter = new ResponseFormatter();

        $this->formatter = new BothFormatter($requestFormatter, $responseFormatter);
    }

    public function testFormatter()
    {
        $request = new ServerRequest([], [], 'https://github.com/php-middleware/log-http-messages', 'GET', 'php://input', ['Accept' => 'text/html']);
        $response = new Response('php://memory', 500, ['Content-Type' => 'text/html']);

        $result = $this->formatter->format($request, $response);

        $this->assertSame("Request: GET /php-middleware/log-http-messages HTTP/1.1\r\nAccept: text/html\r\nHost: github.com; Response HTTP/1.1 500 Internal Server Error\r\nContent-Type: text/html\r\n\r\n", $result);
    }
}