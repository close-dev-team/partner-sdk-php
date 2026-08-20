<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\ImageMessage;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ImageId;
use ClosePartnerSdk\Dto\ImageMessage;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendImageMessageTest extends EndpointTestCase
{
    private EventId $eventId;
    private ChatId $chatId;
    private UserId $userId;
    private ImageMessage $message;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->message = new ImageMessage(new ImageId('CLIM1234567890'));
        $this->givenAnAuthorisedClient();
    }

    private function expectPostTo(string $path, array $body): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher(preg_quote($path, '/')),
                function (RequestInterface $request) use ($path, $body) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals($path, $request->getUri()->getPath());
                    self::assertEquals($body, json_decode($request->getBody()->getContents(), true));

                    return $this->mockResponse([]);
                }
            );
    }

    /** @test */
    public function send_to_all_chats_for_an_event()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/messages/image',
            ['image_id' => 'CLIM1234567890']
        );

        $this->givenSdk()->imageMessage()->sendToAllChatsForEvent($this->eventId, $this->message);
    }

    /** @test */
    public function send_to_all_users_in_a_chat()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/messages/image',
            ['image_id' => 'CLIM1234567890']
        );

        $this->givenSdk()->imageMessage()->sendToAllUsersForChat($this->eventId, $this->chatId, $this->message);
    }

    /** @test */
    public function send_to_a_user_in_all_chats()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/users/' . $this->userId . '/messages/image',
            ['image_id' => 'CLIM1234567890']
        );

        $this->givenSdk()->imageMessage()->sendToUserInAllChats($this->eventId, $this->userId, $this->message);
    }

    /** @test */
    public function send_to_a_user_in_a_chat()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/users/' . $this->userId . '/messages/image',
            ['image_id' => 'CLIM1234567890']
        );

        $this->givenSdk()->imageMessage()->sendToUserInChat(
            $this->eventId,
            $this->chatId,
            $this->userId,
            $this->message
        );
    }

    /** @test */
    public function include_send_push_only_when_it_is_set()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/messages/image',
            ['image_id' => 'CLIM1234567890', 'send_push' => true]
        );

        $this->givenSdk()->imageMessage()->sendToAllChatsForEvent(
            $this->eventId,
            $this->message->withSendPush(true)
        );
    }

    /** @test */
    public function send_push_survives_as_false_when_explicitly_disabled()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/messages/image',
            ['image_id' => 'CLIM1234567890', 'send_push' => false]
        );

        $this->givenSdk()->imageMessage()->sendToAllChatsForEvent(
            $this->eventId,
            $this->message->withSendPush(false)
        );
    }
}
