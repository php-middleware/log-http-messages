<?php

declare (strict_types=1);

namespace PhpMiddleware\LogHttpMessages;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PhpMiddleware\DoublePassCompatibilityTrait;
use PhpMiddleware\LogHttpMessages\Formatter\ResponseFormatter;
use PhpMiddleware\LogHttpMessages\Formatter\ServerRequestFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Log\LoggerInterface as Logger;
use Psr\Log\LogLevel;

final class LogMiddleware implements MiddlewareInterface
{
    use DoublePassCompatibilityTrait;

    const LOG_MESSAGE = 'Request/Response';

    private $logger;
    private $level;
    private $requestFormatter;
    private $responseFormatter;
    private $logMessage;

    public function __construct(
        ServerRequestFormatter $requestFormatter,
        ResponseFormatter $responseFormatter,
        Logger $logger,
        string $level = LogLevel::INFO,
        string $logMessage = self::LOG_MESSAGE
    ) {
        $this->requestFormatter = $requestFormatter;
        $this->responseFormatter = $responseFormatter;
        $this->logger = $logger;
        $this->level = $level;
        $this->logMessage = $logMessage;
    }

    public function process(ServerRequest $request, DelegateInterface $delegate) : Response
    {
        $response = $delegate->process($request);

        $formattedRequest = $this->requestFormatter->formatServerRequest($request);
        $formattedResponse = $this->responseFormatter->formatResponse($response);

        $this->logger->log($this->level, $this->logMessage, [
            'request' => $formattedRequest->getValue(),
            'response' => $formattedResponse->getValue(),
        ]);

        return $response;
    }
}
