<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class TextMessageOperation extends CloseOperation
{
    public function sendToAllChatsForEvent(EventId $eventId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }

    public function sendToAllUsersForChat(EventId $eventId, ChatId $chatId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }

    public function sendToUserInChat(EventId $eventId, ChatId $chatId, UserId $userId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/chats/'.$chatId.'/users/'.$userId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }

    public function sendToUserInAllChats(EventId $eventId, UserId $userId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'/users/'.$userId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }
}