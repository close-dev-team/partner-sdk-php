<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\AdminGrant;
use ClosePartnerSdk\Dto\Carousel;
use ClosePartnerSdk\Dto\Event;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;
use DateTimeInterface;
use JsonException;

final class EventOperation extends CloseOperation
{
    /**
     * @return Event[]
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function getEvents(): array
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $events = [];

        foreach ($obj->events as $eventObj) {
            $event = Event::buildFromResponseObject($eventObj);
            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param EventId $eventId
     * @return Event
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function getEvent(EventId $eventId): Event
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return Event::buildFromResponseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @param array $updates
     * @return Event
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function updateEvent(EventId $eventId, array $updates): Event
    {
        $response = $this->sdk
            ->getHttpClient()
            ->put(
                $this->buildUriWithLatestVersion('/events/' . $eventId),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    $updates
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return Event::buildFromResponseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @return Event
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function copyEvent(EventId $eventId): Event
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/copy'),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return Event::buildFromResponseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @param EventTime $eventTime;
     * @return Event
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function cloneEvent(EventId $eventId, EventTime $eventTime): Event
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/clone'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    [
                        'start_date_time' => $eventTime->getStartDateTime()->format(DateTimeInterface::W3C)
                    ]
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return Event::buildFromResponseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @param string $name
     * @return Carousel
     * @throws JsonException
     */
    public function createCarousel(EventId $eventId, string $name): Carousel
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/carousels'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    ['name' => $name]
                )
            );
        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        return Carousel::buildFromResponseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @param string $name
     * @return Carousel
     * @throws JsonException
     */
    public function lookupCarousel(EventId $eventId, string $name): Carousel
    {
        $response = $this->sdk
            ->getHttpClient()
            ->get(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/carousels?name=' . urlencode($name)),
                [],
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return Carousel::buildFromResponseObject($obj);
    }

    /**
     * Make a user an admin of the event.
     *
     * Idempotent: adding someone who is already an admin changes nothing and
     * answers false.
     *
     * @return bool whether this call is what made them an admin
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function addAdmin(EventId $eventId, UserId $userId): bool
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/admins/' . $userId),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return (bool)($obj->added ?? false);
    }

    /**
     * Drop a user as admin of the event.
     *
     * Idempotent: removing someone who is not an admin changes nothing and
     * answers false.
     *
     * @return bool whether this call is what removed them
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function removeAdmin(EventId $eventId, UserId $userId): bool
    {
        $response = $this->sdk
            ->getHttpClient()
            ->delete(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/admins/' . $userId),
                []
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return (bool)($obj->deleted ?? false);
    }

    /**
     * Make the holder of a phone number an admin, for when the organiser is
     * known but not whether they have an account yet.
     *
     * A pending outcome is a success, not a failure: the grant is recorded
     * and takes effect when that number registers.
     *
     * @throws \Http\Client\Exception
     * @throws JsonException
     */
    public function addAdminByPhoneNumber(EventId $eventId, string $phoneNumber): AdminGrant
    {
        $response = $this->sdk
            ->getHttpClient()
            ->post(
                $this->buildUriWithLatestVersion('/events/' . $eventId . '/admins/by-phone'),
                [],
                RequestBodyMediator::convertStreamFromArray(
                    $this->sdk,
                    ['phone_number' => $phoneNumber]
                )
            );

        $obj = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return AdminGrant::buildFromResponseObject($obj);
    }
}
