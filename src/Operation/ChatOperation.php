<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Chat;
use ClosePartnerSdk\Dto\User;
use ClosePartnerSdk\Dto\UserId;

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

        $chat = new Chat(
            new EventId($obj->event_id),
            new ChatId($obj->chat_id)
        );
        foreach ($obj->users as $user) {
            $userInChat = new User(new UserId($user->user_id));
            if (!empty($user->phone_number)) {
                $userInChat->withPhoneNumber($user->phone_number);
            }
            if (!empty($user->nickname)) {
                $userInChat->withNickname($user->nickname);
            }
            foreach ($user->chat_ids as $chat_id) {
                $userInChat->withChatId(new Chatid($chat_id));
            }

            $chat = $chat->withUser($userInChat);
        }

        return $chat;
    }
}