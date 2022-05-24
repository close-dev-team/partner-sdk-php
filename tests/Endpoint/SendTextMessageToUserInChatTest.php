<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Dto\UserId;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendTextMessageToUserInChatTest extends EndpointTestCase
{
    /** @test **/
    public function call_send_text_message_to_user_in_chat_endpoint()
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
        $chatId = new ChatId('5678');
        $userId = new UserId('9012');

        $this->mockClient
            ->on(
                new RequestMatcher('events/'.$eventId. 'chats/'.$chatId.'users/'.$userId.'/messages/text'),
                function (RequestInterface $request) {
                self::assertEquals(
                    SendMessageMapper::withText('text'),
                    json_decode($request->getBody()->getContents(), true)
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->textMessages()->sendToUserInChat(
            $eventId,
            $chatId,
            $userId,
            'text'
        );
    }
}
