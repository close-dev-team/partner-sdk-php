<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\User;
use ClosePartnerSdk\Dto\UserId;

final class UserOperation extends CloseOperation
{
    /**
     * Every user that joined the event.
     *
     * This response carries a smaller shape than the lookups below: user_id
     * plus whichever identifier the deployment is configured for. Nickname
     * and chat ids are not part of it.
     *
     * @return User[]
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function getUsersForEvent(EventId $eventId): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/users'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $users = [];
        foreach ($obj->data ?? [] as $userObj) {
            $users[] = User::buildFromResponseObject($userObj);
        }

        return $users;
    }

    /**
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function lookupUserById(EventId $eventId, UserId $userId): User
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/users/' . $userId),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return User::buildFromResponseObject($obj);
    }

    /**
     * The phone number is part of the query string, so it has to be encoded:
     * an E164 number starts with a + that would otherwise read as a space.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function lookupUserByPhoneNumber(EventId $eventId, string $phoneNumber): User
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion(
                    '/events/' . $eventId . '/users?phone_number=' . urlencode($phoneNumber)
                ),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return User::buildFromResponseObject($obj);
    }

    /**
     * Check that a user belongs to a chat in an event.
     *
     * The endpoint answers with an empty object, so there is nothing to
     * return: it succeeds, or the API reports the failure and the error
     * plugin turns that into an exception.
     *
     * @throws \Http\Client\Exception
     */
    public function verifyUserInChat(EventId $eventId, ChatId $chatId, UserId $userId): void
    {
        $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion(
                    '/events/' . $eventId . '/chats/' . $chatId . '/users/' . $userId
                ),
                []
            );
    }
}
