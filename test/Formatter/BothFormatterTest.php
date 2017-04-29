<?php

namespace PhpMiddlewareTest\LogHttpMessages\Formatter;

use PhpMiddleware\LogHttpMessages\Formatter\BothFormatter;
use PhpMiddleware\LogHttpMessages\Formatter\RequestFormatter;
use PhpMiddleware\LogHttpMessages\Formatter\ResponseFormatter;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class BothFormatterTest extends TestCase
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

        $this->assertContains('; Response ', $result);
    }
}