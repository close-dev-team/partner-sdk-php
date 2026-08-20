<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Dto\WebWidgetMessage;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class WebWidgetMessageOperation extends CloseOperation
{
    /**
     * @throws \Http\Client\Exception
     */
    public function sendToAllChatsForEvent(EventId $eventId, WebWidgetMessage $message): void
    {
        $this->send('/events/'.$eventId.'/messages/webwidget', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToAllUsersForChat(EventId $eventId, ChatId $chatId, WebWidgetMessage $message): void
    {
        $this->send('/events/'.$eventId.'/chats/'.$chatId.'/messages/webwidget', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToUserInAllChats(EventId $eventId, UserId $userId, WebWidgetMessage $message): void
    {
        $this->send('/events/'.$eventId.'/users/'.$userId.'/messages/webwidget', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToUserInChat(EventId $eventId, ChatId $chatId, UserId $userId, WebWidgetMessage $message): void
    {
        $this->send('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/messages/webwidget', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    private function send(string $endpoint, WebWidgetMessage $message): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion($endpoint),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $message->toArray()
                )
            );
    }
}
