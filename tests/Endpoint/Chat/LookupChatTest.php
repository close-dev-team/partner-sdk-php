<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Chat;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class LookupChatTest extends EndpointTestCase
{
    /** @test */
    public function call_the_chat_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $chatId = new ChatId('CLCH1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/chats/' . $chatId),
                function (RequestInterface $request) use ($eventId, $chatId) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $eventId . '/chats/' . $chatId,
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([
                        'event_id' => (string)$eventId,
                        'chat_id' => (string)$chatId,
                        'users' => [],
                    ]);
                }
            );

        $chat = $this->givenSdk()->chat()->lookupChat($eventId, $chatId);

        self::assertEquals((string)$eventId, (string)$chat->getEventId());
        self::assertEquals((string)$chatId, (string)$chat->getChatId());
        self::assertSame([], $chat->getUsers());
    }

    /** @test */
    public function map_the_users_in_the_chat()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $chatId = new ChatId('CLCH1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/chats/' . $chatId),
                fn() => $this->mockResponse([
                    'event_id' => (string)$eventId,
                    'chat_id' => (string)$chatId,
                    'users' => [
                        [
                            'user_id' => 'CLUS1111111111',
                            'phone_number' => '+31612345678',
                            'nickname' => 'John',
                            'chat_ids' => ['CLCH1234567890', 'CLCH2222222222'],
                        ],
                    ],
                ])
            );

        $users = $this->givenSdk()->chat()->lookupChat($eventId, $chatId)->getUsers();

        self::assertCount(1, $users);
        self::assertEquals('CLUS1111111111', (string)$users[0]->getUserId());
        self::assertEquals('+31612345678', $users[0]->getPhoneNumber());
        self::assertEquals('John', $users[0]->getNickname());
        self::assertCount(2, $users[0]->getChatIds());
    }

    /** @test */
    public function leave_optional_user_fields_empty_when_the_api_omits_them()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $chatId = new ChatId('CLCH1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/chats/' . $chatId),
                fn() => $this->mockResponse([
                    'event_id' => (string)$eventId,
                    'chat_id' => (string)$chatId,
                    'users' => [
                        [
                            'user_id' => 'CLUS1111111111',
                            'phone_number' => '',
                            'nickname' => '',
                            'chat_ids' => [],
                        ],
                    ],
                ])
            );

        $users = $this->givenSdk()->chat()->lookupChat($eventId, $chatId)->getUsers();

        self::assertNull($users[0]->getPhoneNumber());
        self::assertNull($users[0]->getNickname());
        self::assertSame([], $users[0]->getChatIds());
    }
}
