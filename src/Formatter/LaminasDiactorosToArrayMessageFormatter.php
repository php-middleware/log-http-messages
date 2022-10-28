<?php

declare (strict_types=1);

namespace PhpMiddleware\LogHttpMessages\Formatter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\ArraySerializer as ResponseSerializer;
use Laminas\Diactoros\Request\ArraySerializer as RequestSerializer;

final class LaminasDiactorosToArrayMessageFormatter implements ServerRequestFormatter, ResponseFormatter
{
    public function formatResponse(ResponseInterface $response): FormattedMessage
    {
        $array = ResponseSerializer::toArray($response);

        return FormattedMessage::fromArray($array);
    }

    public function formatServerRequest(ServerRequestInterface $request): FormattedMessage
    {
        $array = RequestSerializer::toArray($request);

        return FormattedMessage::fromArray($array);
    }

}
