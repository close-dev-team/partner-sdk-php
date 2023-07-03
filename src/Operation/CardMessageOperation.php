<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

class CardMessageOperation extends CloseOperation
{
    /**
     * @param EventId $eventId
     * @param array $request
     * @return void
     * @throws \Http\Client\Exception
     */
    public function sendToAllChatsForEvent(EventId $eventId, array $request): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/messages/card'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $request
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @param array $request
     * @return void
     * @throws \Http\Client\Exception
     */
    public function sendToAllUsersForChat(EventId $eventId, ChatId $chatId, array $request): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/messages/card'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $request
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param UserId $userId
     * @param array $request
     * @return void
     * @throws \Http\Client\Exception
     */
    public function sendToUserInAllChats(EventId $eventId, UserId $userId, array $request): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/users/'.$userId.'/messages/card'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $request
                )
            );
    }

    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @param UserId $userId
     * @param array $request
     * @return void
     * @throws \Http\Client\Exception
     */
    public function sendToUserInChat(EventId $eventId, ChatId $chatId, UserId $userId, array $request): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/messages/card'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $request
                )
            );
    }
}