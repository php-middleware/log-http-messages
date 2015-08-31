<?php

namespace PhpMiddlewareTest\LogHttpMessages;

use PhpMiddleware\LogHttpMessages\LogResponseMiddleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class LogResponseMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected $logger;
    protected $middleware;

    protected function setUp()
    {
        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->middleware = new LogResponseMiddleware($this->logger);
    }

    public function testLogResponse()
    {
        $request = new ServerRequest();
        $response = new Response('php://memory', 500, ['Content-Type' => 'text/html']);
        $calledOut = false;
        $outFunction = function ($request, $response) use (&$calledOut) {
            $calledOut = true;
            return $response;
        };

        $this->logger->expects($this->once())->method('log')->willReturnCallback(function ($level, $content) {
            $this->assertEquals(LogLevel::INFO, $level);
            $this->assertSame("HTTP/1.1 500 Internal Server Error\r\nContent-Type: text/html", $content);
        });

        $result = call_user_func($this->middleware, $request, $response, $outFunction);

        $this->assertTrue($calledOut, 'Out is not called');
        $this->assertSame($response, $result);
    }
}
