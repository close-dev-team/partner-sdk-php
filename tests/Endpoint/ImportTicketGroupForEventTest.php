<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\Mapper\ImportTicketsMapper;
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

class ImportTicketGroupForEventTest extends EndpointTestCase
{
    /** @test **/
    public function authorise_the_user_before_calling_the_import_tickets_endpoint_with_correct_credentials()
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
            ->on(new RequestMatcher('tickets/import'), function (RequestInterface $request) use ($token) {

                self::assertEquals(["Bearer $token"], $request->getHeader('Authorization'));
                // return a response
                return $this->mockResponse([]);
            });


        $this->givenSdk()->tickets()->importTicket(
            new EventId('1234'),
            new TicketGroup('+31666111000')
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

        $this->givenSdk()->tickets()->importTicket(
            new EventId('1234'),
            new TicketGroup('+31666111000')
        );
    }

    /** @test **/
    public function prevent_calling_authorization_endpoint_for_each_request()
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
        $this->mockClient->addResponse($this->mockResponse([]));
        $this->mockClient->addResponse($this->mockResponse([]));


        $endpoint = $this->givenSdk()->tickets();
        $endpoint->importTicket(
            new EventId('1234'),
            new TicketGroup('+31666111000')
        );
        $endpoint->importTicket(
            new EventId('1234'),
            new TicketGroup('+31666111000')
        );

        $requests = $this->mockClient->getRequests();
        self::assertCount(3, $requests);
    }

    /** @test **/
    public function call_import_endpoint_with_one_ticket_in_request()
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
                new RequestMatcher('tickets/import'),
                function (RequestInterface $request) use($ticketGroup, $eventId) {
                self::assertEquals(
                    ImportTicketsMapper::forTicketGroupAndEvent($ticketGroup, $eventId),
                    json_decode($request->getBody()->getContents(), true)
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->tickets()->importTicket(
            $eventId,
            $ticketGroup
        );
    }
}
