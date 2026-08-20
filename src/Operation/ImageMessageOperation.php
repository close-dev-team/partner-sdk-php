<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ImageMessage;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class ImageMessageOperation extends CloseOperation
{
    /**
     * @throws \Http\Client\Exception
     */
    public function sendToAllChatsForEvent(EventId $eventId, ImageMessage $message): void
    {
        $this->send('/events/'.$eventId.'/messages/image', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToAllUsersForChat(EventId $eventId, ChatId $chatId, ImageMessage $message): void
    {
        $this->send('/events/'.$eventId.'/chats/'.$chatId.'/messages/image', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToUserInAllChats(EventId $eventId, UserId $userId, ImageMessage $message): void
    {
        $this->send('/events/'.$eventId.'/users/'.$userId.'/messages/image', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function sendToUserInChat(EventId $eventId, ChatId $chatId, UserId $userId, ImageMessage $message): void
    {
        $this->send('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/messages/image', $message);
    }

    /**
     * @throws \Http\Client\Exception
     */
    private function send(string $endpoint, ImageMessage $message): void
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
