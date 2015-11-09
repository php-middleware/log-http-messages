<?php

namespace PhpMiddleware\LogHttpMessages\Formatter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Request\Serializer;

final class RequestFormatter implements HttpMessagesFormatter
{
    public function format(ServerRequestInterface $request, ResponseInterface $response)
    {
        return Serializer::toString($request);
    }
}