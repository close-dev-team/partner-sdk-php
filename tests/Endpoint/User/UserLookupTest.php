<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\User;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\User;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class UserLookupTest extends EndpointTestCase
{
    private EventId $eventId;
    private UserId $userId;
    private ChatId $chatId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function fetch_every_user_that_joined_the_event()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users'),
                function (RequestInterface $request) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/users',
                        $request->getUri()->getPath()
                    );
                    self::assertEmpty($request->getUri()->getQuery());

                    return $this->mockResponse([
                        'data' => [
                            ['user_id' => 'CLUS1111111111', 'phone_number' => '+31612345678'],
                            ['user_id' => 'CLUS2222222222', 'phone_number' => '+31687654321'],
                        ],
                    ]);
                }
            );

        $users = $this->givenSdk()->user()->getUsersForEvent($this->eventId);

        self::assertCount(2, $users);
        self::assertContainsOnlyInstancesOf(User::class, $users);
        self::assertEquals('CLUS1111111111', (string)$users[0]->getUserId());
        self::assertEquals('+31612345678', $users[0]->getPhoneNumber());
    }

    /** @test */
    public function the_collection_is_read_from_the_data_envelope()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users'),
                fn() => $this->mockResponse(['data' => []])
            );

        self::assertSame([], $this->givenSdk()->user()->getUsersForEvent($this->eventId));
    }

    /** @test */
    public function carry_the_registration_id_when_the_deployment_uses_one()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users'),
                fn() => $this->mockResponse([
                    'data' => [['user_id' => 'CLUS1111111111', 'registration_id' => 'REG-42']],
                ])
            );

        $users = $this->givenSdk()->user()->getUsersForEvent($this->eventId);

        self::assertEquals('REG-42', $users[0]->getRegistrationId());
        self::assertNull($users[0]->getPhoneNumber());
    }

    /** @test */
    public function look_a_user_up_by_id()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users/' . $this->userId),
                function (RequestInterface $request) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/users/' . $this->userId,
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([
                        'user_id' => (string)$this->userId,
                        'nickname' => 'John',
                        'chat_ids' => ['CLCH1234567890', 'CLCH2222222222'],
                    ]);
                }
            );

        $user = $this->givenSdk()->user()->lookupUserById($this->eventId, $this->userId);

        self::assertEquals((string)$this->userId, (string)$user->getUserId());
        self::assertEquals('John', $user->getNickname());
        self::assertCount(2, $user->getChatIds());
        self::assertNull($user->getPhoneNumber());
    }

    /** @test */
    public function look_a_user_up_by_phone_number()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/users'),
                function (RequestInterface $request) {
                    self::assertEquals('phone_number=%2B31612345678', $request->getUri()->getQuery());

                    return $this->mockResponse([
                        'user_id' => 'CLUS1111111111',
                        'phone_number' => '+31612345678',
                        'nickname' => 'John',
                        'chat_ids' => ['CLCH1234567890'],
                    ]);
                }
            );

        $user = $this->givenSdk()->user()->lookupUserByPhoneNumber($this->eventId, '+31612345678');

        self::assertEquals('CLUS1111111111', (string)$user->getUserId());
        self::assertEquals('+31612345678', $user->getPhoneNumber());
    }

    /** @test */
    public function verify_that_a_user_belongs_to_a_chat()
    {
        $called = false;

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/chats/' . $this->chatId . '/users/' . $this->userId),
                function (RequestInterface $request) use (&$called) {
                    $called = true;
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/users/' . $this->userId,
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->user()->verifyUserInChat($this->eventId, $this->chatId, $this->userId);

        self::assertTrue($called);
    }
}
