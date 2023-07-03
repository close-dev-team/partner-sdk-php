<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Dto\UserId;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendCardMessageToUserInAllChatsTest extends EndpointTestCase
{
    /** @test **/
    public function it_can_send_card_message_to_a_user_in_all_chats()
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
        $userId = new UserId('9012');
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
                new RequestMatcher('events/'.$eventId.'/users/'.$userId.'/messages/card'),
                function (RequestInterface $request) use($body) {
                    self::assertEquals(
                        $body,
                        json_decode($request->getBody()->getContents(), true)
                    );
                    return $this->mockResponse([]);
                });

        $this->givenSdk()->cardMessage()->sendToUserInAllChats(
            $eventId,
            $userId,
            $body
        );
    }
}
