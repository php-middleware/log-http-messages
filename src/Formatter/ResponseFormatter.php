<?php

namespace PhpMiddleware\LogHttpMessages\Formatter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\Serializer;

final class ResponseFormatter implements HttpMessagesFormatter
{
    public function format(ServerRequestInterface $request, ResponseInterface $response)
    {
        return Serializer::toString($response);
    }
}