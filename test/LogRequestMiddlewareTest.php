<?php

namespace PhpMiddlewareTest\LogHttpMessages;

use PhpMiddleware\LogHttpMessages\LogRequestMiddleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class LogRequestMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected $logger;
    protected $middleware;

    protected function setUp()
    {
        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->middleware = new LogRequestMiddleware($this->logger);
    }

    public function testLogRequest()
    {
        $request = new ServerRequest([], [], 'https://github.com/php-middleware/log-http-messages', 'GET', 'php://input', ['Accept' => 'text/html']);
        $response = new Response();
        $calledOut = false;
        $outFunction = function ($request, $response) use (&$calledOut) {
            $calledOut = true;
            return $response;
        };

        $this->logger->expects($this->once())->method('log')->willReturnCallback(function ($level, $content) {
            $this->assertEquals(LogLevel::INFO, $level);
            $this->assertSame("GET /php-middleware/log-http-messages HTTP/1.1\r\nAccept: text/html", $content);
        });

        $result = call_user_func($this->middleware, $request, $response, $outFunction);

        $this->assertTrue($calledOut, 'Out is not called');
        $this->assertSame($response, $result);
    }
}
