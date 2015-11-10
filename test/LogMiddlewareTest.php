<?php

namespace PhpMiddlewareTest\LogHttpMessages;

use PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter;
use PhpMiddleware\LogHttpMessages\LogMiddleware;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogMiddlewareTest extends PHPUnit_Framework_TestCase
{
    protected $middleware;
    protected $formatter;
    protected $logger;
    protected $request;
    protected $response;
    protected $next;
    protected $level;

    protected function setUp()
    {
        $this->request = $this->getMock(ServerRequestInterface::class);
        $this->response = $this->getMock(ResponseInterface::class);
        $this->nextResponse = clone $this->response;
        $this->next = function () {
            return $this->nextResponse;
        };

        $this->formatter = $this->getMock(HttpMessagesFormatter::class);
        $this->logger = $this->getMock(LoggerInterface::class);
        $this->level = LogLevel::ALERT;

        $this->middleware = new LogMiddleware($this->formatter, $this->logger, $this->level);
    }

    public function testLogMessage()
    {
        $this->formatter->expects($this->once())->method('format')->with($this->request, $this->nextResponse)->willReturn('boo');
        $this->logger->expects($this->once())->method('log')->with($this->level, 'boo');

        $response = $this->executeMiddleware();

        $this->assertSame($this->nextResponse, $response);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testTryToLogNullMessage()
    {
        $this->formatter->expects($this->once())->method('format')->willReturn(null);

        $this->executeMiddleware();
    }

    public function executeMiddleware()
    {
        return call_user_func($this->middleware, $this->request, $this->response, $this->next);
    }
}