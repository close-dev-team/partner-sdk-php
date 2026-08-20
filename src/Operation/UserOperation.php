<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\User;
use ClosePartnerSdk\Dto\UserPage;
use ClosePartnerSdk\Dto\UserId;

final class UserOperation extends CloseOperation
{
    /**
     * Every user that joined the event.
     *
     * The endpoint pages at 100 users, so this walks every page and returns
     * the whole set. Use getUsersForEventPage() to page through it by hand.
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
        $users = [];
        $page = 1;

        do {
            $pageOfUsers = $this->getUsersForEventPage($eventId, $page);
            foreach ($pageOfUsers->getUsers() as $user) {
                $users[] = $user;
            }
            $page++;
        } while ($page <= $pageOfUsers->getLastPage());

        return $users;
    }

    /**
     * One page of the users that joined the event, for callers that would
     * rather not pull an entire large event into memory.
     *
     * @throws \Http\Client\Exception
     * @throws \JsonException
     */
    public function getUsersForEventPage(EventId $eventId, int $page = 1): UserPage
    {
        $endpoint = '/events/' . $eventId . '/users';
        if ($page > 1) {
            $endpoint .= '?page=' . $page;
        }

        $response = $this->sdk
            ->getHttpClient()
            ->get($this->buildUriWithLatestVersion($endpoint), []);

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $users = [];
        foreach ($obj->data ?? [] as $userObj) {
            $users[] = User::buildFromResponseObject($userObj);
        }

        return new UserPage(
            $users,
            (int)($obj->meta->current_page ?? $page),
            (int)($obj->meta->last_page ?? $page)
        );
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
