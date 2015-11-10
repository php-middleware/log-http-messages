<?php

namespace PhpMiddleware\LogHttpMessages;

use PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Log\LoggerInterface as Logger;
use Psr\Log\LogLevel;

class LogMiddleware
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
            throw new \UnexpectedValueException(sprintf('%s must return string', HttpMessagesFormatter::class));
        }

        $this->logger->log($this->level, $messages);

        return $outResponse;
    }
}
