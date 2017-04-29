<?php

namespace PhpMiddlewareTest\LogHttpMessages;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PhpMiddleware\LogHttpMessages\Formatter\EmptyMessageFormatter;
use PhpMiddleware\LogHttpMessages\LogMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogMiddlewareTest extends TestCase
{
    private $middleware;
    private $logger;
    private $request;
    private $response;
    private $next;
    private $level;
    private $delegate;
    private $nextResponse;

    protected function setUp()
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->nextResponse = clone $this->response;
        $this->next = function () {
            return $this->nextResponse;
        };
        $this->delegate = $this->createMock(DelegateInterface::class);
        $this->delegate->method('process')->willReturn($this->nextResponse);

        $formatter = new EmptyMessageFormatter();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->level = LogLevel::ALERT;

        $this->middleware = new LogMiddleware($formatter, $formatter, $this->logger, $this->level);
    }

    /**
     * @dataProvider middlewareProvider
     */
    public function testLogFormattedMessages($middlewareExecutor)
    {
        $this->logger->expects($this->once())->method('log')->with($this->level, LogMiddleware::LOG_MESSAGE, ['request' => null, 'response' => null]);

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
