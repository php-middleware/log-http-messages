<?php

namespace PhpMiddlewareTest\LogHttpMessages;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter;
use PhpMiddleware\LogHttpMessages\LogMiddleware;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use UnexpectedValueException;

class LogMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public $middleware;
    protected $formatter;
    protected $logger;
    protected $request;
    protected $response;
    protected $next;
    protected $level;
    protected $delegate;
    protected $nextResponse;

    protected function setUp()
    {
        $this->request = $this->getMock(ServerRequestInterface::class);
        $this->response = $this->getMock(ResponseInterface::class);
        $this->nextResponse = clone $this->response;
        $this->next = function () {
            return $this->nextResponse;
        };
        $this->delegate = $this->getMock(DelegateInterface::class);
        $this->delegate->method('process')->willReturn($this->nextResponse);

        $this->formatter = $this->getMock(HttpMessagesFormatter::class);
        $this->logger = $this->getMock(LoggerInterface::class);
        $this->level = LogLevel::ALERT;

        $this->middleware = new LogMiddleware($this->formatter, $this->logger, $this->level);
    }

    /**
     * @dataProvider middlewareProvider
     */
    public function testLogFormattedMessages($middlewareExecutor)
    {
        $this->formatter->method('format')->with($this->request, $this->nextResponse)->willReturn('formattedMessages');
        $this->logger->expects($this->once())->method('log')->with($this->level, 'formattedMessages');

        $middlewareExecutor($this);
    }

    /**
     * @dataProvider middlewareProvider
     */
    public function testTryToLogNullMessage($middlewareExecutor)
    {
        $this->formatter->method('format')->willReturn(null);

        $this->setExpectedException(UnexpectedValueException::class);

        $middlewareExecutor($this);
    }

    public function middlewareProvider()
    {
        return [
            'double pass' => [function ($test) {
                return $test->executeDoublePassMiddleware();
            }],
            'single pass' => [function ($test) {
                return $test->executeSinglePassMiddleware();
            }],
        ];
    }

    protected function executeDoublePassMiddleware()
    {
        return call_user_func($this->middleware, $this->request, $this->response, $this->next);
    }

    protected function executeSinglePassMiddleware()
    {
        return $this->middleware->process($this->request, $this->delegate);
    }
}
