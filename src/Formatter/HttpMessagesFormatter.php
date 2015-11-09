<?php

namespace PhpMiddleware\LogHttpMessages\Formatter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpMessagesFormatter
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function format(ServerRequestInterface $request, ResponseInterface $response);
}
