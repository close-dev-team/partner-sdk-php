<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Message\RequestMatcher\RequestMatcher;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SendTextMessageToAllChatsTest extends EndpointTestCase
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
        $eventId = new EventId('1234');
        $this->mockClient
            ->on(new RequestMatcher('events/'.$eventId.'/messages/text'), function (RequestInterface $request) use ($token) {

                self::assertEquals(["Bearer $token"], $request->getHeader('Authorization'));
                // return a response
                return $this->mockResponse([]);
            });


        $this->givenSdk()->sendMessage()->toAllChatsForShow(
            $eventId,
            'text',
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

        $this->givenSdk()->sendMessage()->toAllChatsForShow(
            new EventId('1234'),
            'text'
        );
    }

    /** @test **/
    public function call_send_text_message_to_all_chats_endpoint()
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
                new RequestMatcher('events/'.$eventId.'/messages/text'),
                function (RequestInterface $request) {
                self::assertEquals(
                    SendMessageMapper::withText('text'),
                    json_decode($request->getBody()->getContents(), true)
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->sendMessage()->toAllChatsForShow(
            $eventId,
            'text'
        );
    }
}
