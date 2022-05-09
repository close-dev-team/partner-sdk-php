<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Message\RequestMatcher\RequestMatcher;
use Laminas\Diactoros\Request;
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


        $this->givenSdk()->importTickets()->withTicketGroupAndEventId(
            new EventId('1234'),
            new TicketGroup
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
                        new Request(),
                        $response
                    );
                }
            );

        $this->expectException(InvalidCredentialsException::class);

        $this->givenSdk()->importTickets()->withTicketGroupAndEventId(
            new EventId('1234'),
            new TicketGroup
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


        $endpoint = $this->givenSdk()->importTickets();
        $endpoint->withTicketGroupAndEventId(
            new EventId('1234'),
            new TicketGroup
        );
        $endpoint->withTicketGroupAndEventId(
            new EventId('1234'),
            new TicketGroup
        );

        $requests = $this->mockClient->getRequests();
        self::assertCount(3, $requests);
    }
}
