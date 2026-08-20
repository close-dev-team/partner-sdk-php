<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\WebWidgetMessage;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Dto\WebWidgetMessage;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SendWebWidgetMessageTest extends EndpointTestCase
{
    private EventId $eventId;
    private ChatId $chatId;
    private UserId $userId;
    private WebWidgetMessage $message;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->userId = new UserId('CLUS1234567890');
        $this->message = WebWidgetMessage::withUrl('320', '480', 'https://example.org/widget');
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
            '/api/v1/events/' . $this->eventId . '/messages/webwidget',
            ['width' => '320', 'height' => '480', 'url' => 'https://example.org/widget']
        );

        $this->givenSdk()->webWidgetMessage()->sendToAllChatsForEvent($this->eventId, $this->message);
    }

    /** @test */
    public function send_to_all_users_in_a_chat()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/messages/webwidget',
            ['width' => '320', 'height' => '480', 'url' => 'https://example.org/widget']
        );

        $this->givenSdk()->webWidgetMessage()->sendToAllUsersForChat($this->eventId, $this->chatId, $this->message);
    }

    /** @test */
    public function send_to_a_user_in_all_chats()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/users/' . $this->userId . '/messages/webwidget',
            ['width' => '320', 'height' => '480', 'url' => 'https://example.org/widget']
        );

        $this->givenSdk()->webWidgetMessage()->sendToUserInAllChats($this->eventId, $this->userId, $this->message);
    }

    /** @test */
    public function send_to_a_user_in_a_chat()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/users/' . $this->userId . '/messages/webwidget',
            ['width' => '320', 'height' => '480', 'url' => 'https://example.org/widget']
        );

        $this->givenSdk()->webWidgetMessage()->sendToUserInChat(
            $this->eventId,
            $this->chatId,
            $this->userId,
            $this->message
        );
    }

    /** @test */
    public function send_html_instead_of_a_url()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/messages/webwidget',
            ['width' => '320', 'height' => '480', 'html' => '<b>Hello</b>']
        );

        $this->givenSdk()->webWidgetMessage()->sendToAllChatsForEvent(
            $this->eventId,
            WebWidgetMessage::withHtml('320', '480', '<b>Hello</b>')
        );
    }

    /** @test */
    public function add_a_push_notification_message()
    {
        $this->expectPostTo(
            '/api/v1/events/' . $this->eventId . '/messages/webwidget',
            [
                'width' => '320',
                'height' => '480',
                'url' => 'https://example.org/widget',
                'push_notification_message' => 'Look at this',
            ]
        );

        $this->givenSdk()->webWidgetMessage()->sendToAllChatsForEvent(
            $this->eventId,
            $this->message->withPushNotificationMessage('Look at this')
        );
    }
}
