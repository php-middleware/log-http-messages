<?php

namespace PhpMiddlewareTest\LogHttpMessages\Formatter;

use PHPUnit\Framework\TestCase;
use PhpMiddleware\LogHttpMessages\Formatter\LaminasDiactorosToArrayMessageFormatter;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

class LaminasDiactorosToArrayMessageFormatterTest extends TestCase
{
    public function testFormatRequestToArray()
    {
        $request = new ServerRequest();
        $formatter = new LaminasDiactorosToArrayMessageFormatter();

        $formattedMessage = $formatter->formatServerRequest($request);

        $this->assertIsArray($formattedMessage->getValue());
    }

    public function testFormatResponeToArray()
    {
        $response = new Response();
        $formatter = new LaminasDiactorosToArrayMessageFormatter();

        $formattedMessage = $formatter->formatResponse($response);

        $this->assertIsArray($formattedMessage->getValue());
    }
}
