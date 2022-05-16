<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Endpoint;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Mapper\SendMessageMapper;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class SendMessage extends CloseEndpoint
{
    public function toAllChatsForShow(EventId $eventId, string $text): void
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

    public function toAllUsersForChat(EventId $eventId, ChatId $chatId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'chats/'.$chatId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }

    public function toUserInChat(EventId $eventId, ChatId $chatId, UserId $userId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'chats/'.$chatId.'users/'.$userId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }

    public function toUserInAllChats(EventId $eventId, UserId $userId, string $text): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/'.$eventId.'users/'.$userId.'/messages/text'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    SendMessageMapper::withText($text)
                )
            );
    }
}