<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Operation;

use ClosePartnerSdk\Dto\Event;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\HttpClient\Message\RequestBodyMediator;

final class EventOperation extends CloseOperation
{
    /**
     * @return Event[]
     * @throws \Http\Client\Exception
     * @throws \JsonException
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
            $event = Event::buildFromRepsonseObject($eventObj);
            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param EventId $eventId
     * @return Event
     * @throws \Http\Client\Exception
     * @throws \JsonException
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
        return Event::buildFromRepsonseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @param array $updates
     * @return Event
     * @throws \Http\Client\Exception
     * @throws \JsonException
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
        return Event::buildFromRepsonseObject($obj);
    }

    /**
     * @param EventId $eventId
     * @return Event
     * @throws \Http\Client\Exception
     * @throws \JsonException
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
        return Event::buildFromRepsonseObject($obj);
    }
}