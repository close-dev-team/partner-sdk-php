<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use ClosePartnerSdk\Exception\InvalidRequestJsonFormat;
use ClosePartnerSdk\Tests\Factory\Dto\TicketCancelFactory;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Message\RequestMatcher\RequestMatcher;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CreateCarouselForEventTest extends EndpointTestCase
{
    /** @test */
    public function it_does_not_have_the_required_field()
    {
        $token = 'ey8393930dkdkdk';
        $this->mockClient
            ->on(
                new RequestMatcher('oauth/token'),
                fn() => $this->mockResponse([
                    "token_type" => "Bearer",
                    "expires_in" => 1113,
                    "access_token" => $token,
                ])
            );
        $eventId = new EventId('1234');
        $this->mockClient
            ->on(
                new RequestMatcher('events/'.$eventId.'/carousels'),
                function (RequestInterface $request) {
                    /** @var MockObject|ResponseInterface $response */
                    $response = $this->mockResponse([]);
                    $response
                        ->method('getStatusCode')
                        ->willReturn(422);
                    throw new InvalidRequestJsonFormat(
                        "The name attribute is required",
                        $this->createMock(RequestInterface::class),
                        $response
                    );
                });

        $this->expectException(InvalidRequestJsonFormat::class);

        $this->givenSdk()->event()->createCarousel(
            $eventId,
            '',
        );
    }

    /** @test */
    public function it_creates_a_new_carousel_for_the_event()
    {
        $token = 'ey8393930dkdkdk';
        $this->mockClient
            ->on(
                new RequestMatcher('oauth/token'),
                fn() => $this->mockResponse([
                    "token_type" => "Bearer",
                    "expires_in" => 1113,
                    "access_token" => $token,
                ])
            );
        $eventId = new EventId('1234');
        $this->mockClient
            ->on(
                new RequestMatcher('events/'.$eventId.'/carousels'),
                function (RequestInterface $request) {
                    $response = json_decode($request->getBody()->getContents(), true);
                    self::assertEquals(
                        'text',
                        $response['name']
                    );
                    return $this->mockResponse([]);
                });

        $this->expectException(InvalidRequestJsonFormat::class);

        $this->givenSdk()->event()->createCarousel(
            $eventId,
            'text',
        );
    }
}
