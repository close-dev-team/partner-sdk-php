<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Chat;
use ClosePartnerSdk\Dto\Survey\Survey;
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

    /**
     * Survey answers collected in a chat.
     *
     * Which parts of each survey are filled depends on its type: a button
     * survey reports options, a slider or text survey reports questions.
     *
     * @return Survey[]
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function getSurveyResults(EventId $eventId, ChatId $chatId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion(
                    '/events/' . $eventId . '/chats/' . $chatId . '/survey-results'
                ),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $surveys = [];
        foreach ($obj->surveys ?? [] as $survey) {
            $surveys[] = Survey::buildFromResponseObject($survey);
        }

        return $surveys;
    }

    /**
     * Give an existing chat access to a second event.
     *
     * Note the path puts the chat first: this is scoped to the chat rather
     * than to the event, unlike the rest of the SDK.
     *
     * @throws \Http\Client\Exception
     */
    public function addEventToChat(ChatId $chatId, EventId $eventId): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/chats/' . $chatId . '/event/' . $eventId . '/add'),
                []
            );
    }

    /**
     * @throws \Http\Client\Exception
     */
    public function deleteEventFromChat(ChatId $chatId, EventId $eventId): void
    {
        $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/chats/' . $chatId . '/event/' . $eventId . '/delete'),
                []
            );
    }
}
