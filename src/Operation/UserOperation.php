<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\Event;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Dto\User;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;
use Http\Client\Common\Exception\ClientErrorException;


final class UserOperation extends CloseOperation
{
    public function userExists(EventId $eventId, ChatId $chatId, UserId $userId): bool
    {
        try {
            $response = $this->sdk
                ->getHttpClient()
                ->get(
                    uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/chats/' . $chatId . '/users/' . $userId),
                    headers: []
                );
        } catch (ClientErrorException $exception) {
            return false;
        }

        if ($response->getStatusCode() === 200) {
            return true;
        }

        return false;
    }

    public function lookupUserByPhoneNumber(EventId $eventId, string $phoneNumber): User
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/users?phone_number=' . urlencode($phoneNumber)),
                headers: []
            );
        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return User::buildFromRepsonseObject($obj);
    }

    public function lookupUserByUserId(EventId $eventId, UserId $userId): User
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/users/' . $userId),
                headers: []
            );
        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return User::buildFromRepsonseObject($obj);
    }

    public function getUserIds(EventId $eventId): array
    {
        $userIds = [];

        $page = 1;
        $lastPage = 1;

        do {
            $response = $this->sdk
                ->getHttpClient()
                ->get(
                    uri: $this->buildUriWithLatestVersion('/events/' . $eventId . '/users?page=' . urlencode((string)$page)),
                    headers: []
                );
            $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            var_dump($obj);
            foreach ($obj->data as $userObj) {
                $userIds[] = new UserId($userObj->user_id);
            }

            $lastPage = $obj->meta->last_page;
            $page += 1;
        } while ($page <= $lastPage);

        return $userIds;
    }
}