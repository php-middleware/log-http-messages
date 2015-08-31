<?php

namespace PhpMiddleware\LogHttpMessages;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

abstract class AbstractLogMiddleware
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var int
     */
    protected $level;

    /**
     * @param LoggerInterface $logger
     * @param int $level
     */
    public function __construct(LoggerInterface $logger, $level = LogLevel::INFO)
    {
        $this->logger = $logger;
        $this->level = $level;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $out
     *
     * @return ResponseInterface
     */
    final public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $outResponse = $out($request, $response);

        $contentToLog = $this->getContentToLog($request, $outResponse);

        $this->logger->log($this->level, $contentToLog);

        return $outResponse;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    abstract protected function getContentToLog(ServerRequestInterface $request, ResponseInterface $response);
}
