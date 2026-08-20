<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Chat;
use ClosePartnerSdk\Dto\User;

final class ChatOperation extends CloseOperation
{
    /**
     * @param EventId $eventId
     * @param ChatId $chatId
     * @return Chat
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function lookupChat(EventId $eventId, ChatId $chatId): Chat
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/chats/' . $chatId),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $chat = (new Chat(
            new EventId($obj->event_id),
            new ChatId($obj->chat_id)
        ))->withAdminUserId($obj->admin_user_id ?? null);
        foreach ($obj->users as $user) {
            $chat = $chat->withUser(User::buildFromResponseObject($user));
        }

        return $chat;
    }
}