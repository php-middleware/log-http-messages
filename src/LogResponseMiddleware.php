<?php

namespace PhpMiddleware\LogHttpMessages;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\Serializer;

class LogResponseMiddleware extends AbstractLogMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    protected function getContentToLog(ServerRequestInterface $request, ResponseInterface $response)
    {
        return Serializer::toString($response);
    }
}
