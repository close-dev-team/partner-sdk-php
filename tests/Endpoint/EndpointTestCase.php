<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\ClientBuilder;
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Options;
use Http\Mock\Client;
use JsonException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class EndpointTestCase extends TestCase
{
    protected Client $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = new Client();
    }

    /**
     * @throws JsonException
     */
    protected function mockResponseForClient($response): void
    {
        $responseObject = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->method('getContents')
            ->willReturn(json_encode($response));
        $responseObject
            ->method('getBody')
            ->willReturn($stream);
        $this->mockClient->addResponse($responseObject);
    }

    protected function givenSdk(): CloseSdk
    {
        return new CloseSdk(new Options([
            'client_builder' => new ClientBuilder($this->mockClient),
            'client_id' => 'client_test',
            'client_secret' => 'client_test_secret',
        ]));
    }
}