<?php

namespace PhpMiddleware\LogHttpMessages;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerInterface as Logger;
use Psr\Log\LogLevel;
use UnexpectedValueException;

class LogMiddleware implements MiddlewareInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var HttpMessagesFormatter
     */
    protected $formatter;

    /**
     * @param LoggerInterface $logger
     * @param int $level
     */
    public function __construct(HttpMessagesFormatter $formatter, Logger $logger, $level = LogLevel::INFO)
    {
        $this->formatter = $formatter;
        $this->logger = $logger;
        $this->level = $level;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $outResponse = $next($request, $response);

        $messages = $this->formatter->format($request, $outResponse);

        if (!is_string($messages)) {
            throw new UnexpectedValueException(sprintf('%s must return string', HttpMessagesFormatter::class));
        }

        $this->logger->log($this->level, $messages);

        return $outResponse;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        
    }

}
