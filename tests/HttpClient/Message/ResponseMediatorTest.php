<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\HttpClient\Message;

use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;
use ClosePartnerSdk\HttpClient\Message\ResponseMediator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseMediatorTest extends TestCase
{
    /** @test **/
    public function provide_empty_array_when_response_is_emtpy()
    {
        self::assertEquals([], ResponseMediator::getContent($this->mockResponse('')));
    }

    /** @test **/
    public function inform_when_the_response_is_not_a_correct_json()
    {
        $this->expectException(InvalidResponseJsonFormat::class);

        ResponseMediator::getContent($this->mockResponse('I am a teapot'));
    }

    private function mockResponse($response): ResponseInterface
    {
        $responseObject = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->method('getContents')
            ->willReturn($response);
        $responseObject
            ->method('getBody')
            ->willReturn($stream);

        return $responseObject;
    }
}
