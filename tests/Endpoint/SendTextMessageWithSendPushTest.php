<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendTextMessageWithSendPushTest extends EndpointTestCase
{
    private EventId $eventId;
    private ChatId $chatId;
    private UserId $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->givenAnAuthorisedClient();
    }

    private function expectBody(array $body): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/messages/text'),
                function (RequestInterface $request) use ($body) {
                    self::assertEquals($body, json_decode($request->getBody()->getContents(), true));

                    return $this->mockResponse([]);
                }
            );
    }

    /** @test */
    public function leave_send_push_out_of_the_request_by_default()
    {
        $this->expectBody(['text' => 'Hello']);

        $this->givenSdk()->textMessage()->sendToAllChatsForEvent($this->eventId, 'Hello');
    }

    /** @test */
    public function ask_for_a_push_notification()
    {
        $this->expectBody(['text' => 'Hello', 'send_push' => true]);

        $this->givenSdk()->textMessage()->sendToAllChatsForEvent($this->eventId, 'Hello', true);
    }

    /** @test */
    public function suppress_the_push_notification()
    {
        $this->expectBody(['text' => 'Hello', 'send_push' => false]);

        $this->givenSdk()->textMessage()->sendToAllChatsForEvent($this->eventId, 'Hello', false);
    }

    /** @test */
    public function send_push_reaches_the_chat_scoped_endpoint()
    {
        $this->expectBody(['text' => 'Hello', 'send_push' => false]);

        $this->givenSdk()->textMessage()->sendToAllUsersForChat(
            $this->eventId,
            $this->chatId,
            'Hello',
            false
        );
    }

    /** @test */
    public function send_push_reaches_the_user_scoped_endpoint()
    {
        $this->expectBody(['text' => 'Hello', 'send_push' => true]);

        $this->givenSdk()->textMessage()->sendToUserInAllChats(
            $this->eventId,
            $this->userId,
            'Hello',
            true
        );
    }

    /** @test */
    public function send_push_reaches_the_user_in_chat_endpoint()
    {
        $this->expectBody(['text' => 'Hello', 'send_push' => true]);

        $this->givenSdk()->textMessage()->sendToUserInChat(
            $this->eventId,
            $this->chatId,
            $this->userId,
            'Hello',
            true
        );
    }
}
