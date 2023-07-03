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

class SendCardMessageToAllChatsTest extends EndpointTestCase
{
    /** @test * */
    public function it_does_not_have_the_required_fields()
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
        $body = [];

        $this->mockClient
            ->on(
                new RequestMatcher('events/' . $eventId . '/messages/card'),
                function (RequestInterface $request) use ($body) {
                    $response = $this->mockResponse([]);
                    $response
                        ->method('getStatusCode')
                        ->willReturn(422);

                    self::assertEquals(
                        422,
                        $response->getStatusCode()
                    );
                });

        $this->expectException(\Throwable::class);

        $this->givenSdk()->cardMessage()->sendToAllChatsForEvent(
            $eventId,
            $body
        );
    }

    /** @test * */
    public function it_can_send_a_card_message_to_all_events()
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
        $body = [
            "title" => "Special offer!",
            "image_id" => "CLIM05D3FA6NOOUFJUPC5B5Y7V0NS6",
            "text" => "First paragraph for {user.nickname}",
            "link" => "https =>//thecloseapp.com/",
            "push_notification_message" => "Click to see our special offers for you.",
            "button_text" => "O P E N",
            "open_link_in_app" => false,
            "detail_view_description_1" => "extra paragraph 1\nevent",
            "detail_view_title_2" => "Heading 2",
            "detail_view_description_2" => "extra paragraph 2"
        ];

        $this->mockClient
            ->on(
                new RequestMatcher('events/' . $eventId . '/messages/card'),
                function (RequestInterface $request) use ($body) {
                    $response = json_decode($request->getBody()->getContents(), true);
                    self::assertEquals(
                        $body,
                        $response
                    );
                    return $this->mockResponse([]);
                });

        $this->givenSdk()->cardMessage()->sendToAllChatsForEvent(
            $eventId,
            $body
        );
    }
}
