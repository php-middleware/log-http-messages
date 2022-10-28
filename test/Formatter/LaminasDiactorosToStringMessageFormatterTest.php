<?php

namespace PhpMiddlewareTest\LogHttpMessages\Formatter;

use PhpMiddleware\LogHttpMessages\Formatter\LaminasDiactorosToStringMessageFormatter;
use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

class LaminasDiactorosToStringMessageFormatterTest extends TestCase
{
    public function testFormatRequestToArray()
    {
        $request = new ServerRequest();
        $formatter = new LaminasDiactorosToStringMessageFormatter();

        $formattedMessage = $formatter->formatServerRequest($request);

        $this->assertIsString($formattedMessage->getValue());
    }

    public function testFormatResponeToArray()
    {
        $response = new Response();
        $formatter = new LaminasDiactorosToStringMessageFormatter();

        $formattedMessage = $formatter->formatResponse($response);

        $this->assertIsString($formattedMessage->getValue());
    }
}
