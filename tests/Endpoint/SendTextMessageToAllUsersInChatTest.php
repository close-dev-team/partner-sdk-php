<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendTextMessageToAllUsersInChatTest extends EndpointTestCase
{
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
        $chatId = new ChatId('5678');

        $this->mockClient
            ->on(
                new RequestMatcher('events/'.$eventId. '/chats/'.$chatId.'/messages/text'),
                function (RequestInterface $request) {
                self::assertEquals(
                    SendMessageMapper::withTextAndSendPush('text', true),
                    json_decode($request->getBody()->getContents(), true)
                );
                return $this->mockResponse([]);
            });

        $this->givenSdk()->textMessage()->sendToAllUsersForChat(
            $eventId,
            $chatId,
            'text'
        );
    }
}
