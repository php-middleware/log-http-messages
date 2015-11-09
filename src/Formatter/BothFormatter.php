<?php

namespace PhpMiddleware\LogHttpMessages\Formatter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BothFormatter implements HttpMessagesFormatter
{
    private $requestFormatter;
    private $responseFormatter;

    /**
     * @param RequestFormatter $requestFormatter
     * @param ResponseFormatter $responseFormatter
     */
    public function __construct(RequestFormatter $requestFormatter, ResponseFormatter $responseFormatter)
    {
        $this->requestFormatter = $requestFormatter;
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function format(ServerRequestInterface $request, ResponseInterface $response)
    {
        $requestString = $this->requestFormatter->format($request, $response);
        $reponseString = $this->responseFormatter->format($request, $response);

        return sprintf('Request: %s; Response %s', $requestString, $reponseString);
    }
}