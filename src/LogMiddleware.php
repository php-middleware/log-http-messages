<?php

declare (strict_types=1);

namespace PhpMiddleware\LogHttpMessages;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PhpMiddleware\LogHttpMessages\Formatter\HttpMessagesFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Log\LoggerInterface as Logger;
use Psr\Log\LogLevel;
use UnexpectedValueException;

final class LogMiddleware implements MiddlewareInterface
{
    protected $logger;

    protected $level;

    protected $formatter;

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
     * @return Response
     */
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $outResponse = $next($request, $response);

        $this->logMessages($request, $outResponse);

        return $outResponse;
    }

    /**
     * @param ServerRequest $request
     * @param DelegateInterface $delegate
     *
     * @return Response
     */
    public function process(ServerRequest $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);

        $this->logMessages($request, $response);

        return $response;
    }

    /**
     * @param ServerRequest $request
     * @param Response $response
     *
     * @throws UnexpectedValueException
     */
    private function logMessages(ServerRequest $request, Response $response)
    {
        $messages = $this->formatter->format($request, $response);

        if (!is_string($messages)) {
            throw new UnexpectedValueException(sprintf('%s must return string', HttpMessagesFormatter::class));
        }

        $this->logger->log($this->level, $messages);
    }

}
