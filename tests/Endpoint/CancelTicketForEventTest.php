<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\Mapper\CancelTicketMapper;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use ClosePartnerSdk\Tests\Factory\Dto\TicketGroupFactory;
use DateTime;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Message\RequestMatcher\RequestMatcher;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CancelTicketForEventTest extends EndpointTestCase
{
    /** @test **/
    public function authorise_the_user_before_calling_the_cancel_ticket_endpoint_with_correct_credentials()
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
        $this->mockClient
            ->on(new RequestMatcher('tickets/cancel'), function (RequestInterface $request) use ($token) {

                self::assertEquals(["Bearer $token"], $request->getHeader('Authorization'));
                // return a response
                return $this->mockResponse([]);
            });


        $this->givenSdk()->cancelTicket()->withTicketAndEventId(
            new EventId('1234'),
            new TicketGroup('+31666111000'),
            new Ticket('scan_code', new Product('ticket'), new EventTime(new DateTime()), 1),
        );
    }

    /** @test **/
    public function not_perform_any_call_if_credentials_are_invalid()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('oauth/token'),
                function() {
                    /** @var MockObject|ResponseInterface $response */
                    $response = $this->mockResponse([]);
                    $response
                        ->method('getStatusCode')
                        ->willReturn(401);
                    throw new ClientErrorException(
                        "The credentials are wrong.",
                        $this->createMock(RequestInterface::class),
                        $response
                    );
                }
            );

        $this->expectException(InvalidCredentialsException::class);

        $this->givenSdk()->cancelTicket()->withTicketAndEventId(
            new EventId('1234'),
            new TicketGroup('+31666111000'),
            new Ticket('scan_code', new Product('ticket'), new EventTime(new DateTime()), 1),
        );
    }

    /** @test **/
    public function call_cancel_endpoint_cancelling_one_ticket()
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
        $ticketGroup = TicketGroupFactory::createWithOneTicket();
        $eventId = new EventId('1234');

        $this->mockClient
            ->on(
                new RequestMatcher('tickets/cancel'),
                function (RequestInterface $request) use($ticketGroup, $eventId) {
                self::assertEquals(
                    CancelTicketMapper::forTicketAndEvent($ticketGroup, $ticketGroup->getTickets()[0], $eventId),
                    json_decode($request->getBody()->getContents(), true)
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->cancelTicket()->withTicketAndEventId(
            $eventId,
            $ticketGroup,
            $ticketGroup->getTickets()[0]
        );
    }
}
