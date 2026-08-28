<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use Http\Message\RequestMatcher\RequestMatcher;

/**
 * EventDto::getPhotoImageUrl() and getBackgroundImageUrl() are the only two
 * event fields the API declares nullable, and it sends null for an event that
 * has no artwork.
 */
class EventWithoutImagesTest extends EndpointTestCase
{
    private EventId $eventId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->givenAnAuthorisedClient();
    }

    private function givenEventPayload(array $payload): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId),
                fn() => $this->mockResponse($payload)
            );
    }

    /** @test */
    public function an_event_without_artwork_still_maps()
    {
        $this->givenEventPayload(EventResponseFactory::create([
            'photo_image_url' => null,
            'background_image_url' => null,
        ]));

        $event = $this->givenSdk()->event()->getEvent($this->eventId);

        self::assertNull($event->getPhotoImageUrl());
        self::assertNull($event->getBackgroundImageUrl());
        self::assertEquals('The musical', $event->getName());
    }

    /** @test */
    public function only_the_photo_may_be_missing()
    {
        $this->givenEventPayload(EventResponseFactory::create(['photo_image_url' => null]));

        $event = $this->givenSdk()->event()->getEvent($this->eventId);

        self::assertNull($event->getPhotoImageUrl());
        self::assertEquals('https://example.org/background.png', $event->getBackgroundImageUrl());
    }

    /** @test */
    public function the_keys_may_be_absent_altogether()
    {
        $payload = EventResponseFactory::create();
        unset($payload['photo_image_url'], $payload['background_image_url']);

        $this->givenEventPayload($payload);

        $event = $this->givenSdk()->event()->getEvent($this->eventId);

        self::assertNull($event->getPhotoImageUrl());
        self::assertNull($event->getBackgroundImageUrl());
    }

    /** @test */
    public function a_list_of_events_survives_one_without_artwork()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events'),
                fn() => $this->mockResponse(['events' => [
                    EventResponseFactory::create(['event_id' => 'CLEV1111111111']),
                    EventResponseFactory::create(['event_id' => 'CLEV2222222222', 'photo_image_url' => null]),
                ]])
            );

        $events = $this->givenSdk()->event()->getEvents();

        self::assertCount(2, $events);
        self::assertEquals('https://example.org/photo.png', $events[0]->getPhotoImageUrl());
        self::assertNull($events[1]->getPhotoImageUrl());
    }
}
