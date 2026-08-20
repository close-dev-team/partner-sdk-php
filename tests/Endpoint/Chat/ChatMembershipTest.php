<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Chat;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class ChatMembershipTest extends EndpointTestCase
{
    private EventId $eventId;
    private ChatId $chatId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->givenAnAuthorisedClient();
    }

    /** @test */
    public function add_an_event_to_a_chat()
    {
        $called = false;

        $this->mockClient
            ->on(
                new RequestMatcher('/chats/' . $this->chatId . '/event/' . $this->eventId . '/add'),
                function (RequestInterface $request) use (&$called) {
                    $called = true;
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/chats/' . $this->chatId . '/event/' . $this->eventId . '/add',
                        $request->getUri()->getPath()
                    );
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->chat()->addEventToChat($this->chatId, $this->eventId);

        self::assertTrue($called);
    }

    /** @test */
    public function delete_an_event_from_a_chat()
    {
        $called = false;

        $this->mockClient
            ->on(
                new RequestMatcher('/chats/' . $this->chatId . '/event/' . $this->eventId . '/delete'),
                function (RequestInterface $request) use (&$called) {
                    $called = true;
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/chats/' . $this->chatId . '/event/' . $this->eventId . '/delete',
                        $request->getUri()->getPath()
                    );
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->chat()->deleteEventFromChat($this->chatId, $this->eventId);

        self::assertTrue($called);
    }

    /** @test */
    public function the_chat_comes_before_the_event_in_the_path()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/chats/'),
                function (RequestInterface $request) {
                    self::assertStringStartsWith(
                        '/api/v1/chats/' . $this->chatId . '/event/',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse([]);
                }
            );

        $this->givenSdk()->chat()->addEventToChat($this->chatId, $this->eventId);
    }
}
