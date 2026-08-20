<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\Event;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class GetEventsTest extends EndpointTestCase
{
    /** @test */
    public function call_the_events_endpoint()
    {
        $this->givenAnAuthorisedClient();

        $this->mockClient
            ->on(
                new RequestMatcher('/events'),
                function (RequestInterface $request) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals('/api/v1/events', $request->getUri()->getPath());

                    return $this->mockResponse([
                        'events' => [
                            EventResponseFactory::create(['event_id' => 'CLEV1111111111']),
                            EventResponseFactory::create(['event_id' => 'CLEV2222222222']),
                        ],
                    ]);
                }
            );

        $events = $this->givenSdk()->event()->getEvents();

        self::assertCount(2, $events);
        self::assertContainsOnlyInstancesOf(Event::class, $events);
        self::assertEquals('CLEV1111111111', (string)$events[0]->getEventId());
        self::assertEquals('CLEV2222222222', (string)$events[1]->getEventId());
    }

    /** @test */
    public function map_every_field_of_an_event()
    {
        $this->givenAnAuthorisedClient();

        $this->mockClient
            ->on(
                new RequestMatcher('/events'),
                fn() => $this->mockResponse(['events' => [EventResponseFactory::create()]])
            );

        $event = $this->givenSdk()->event()->getEvents()[0];

        self::assertEquals('Close', $event->getPublisherName());
        self::assertEquals('ABCD', $event->getCode());
        self::assertEquals('2026-01-01T10:00:00+01:00', $event->getStartDateTime());
        self::assertEquals('2026-01-01T18:00:00+01:00', $event->getEndDateTime());
        self::assertEquals('The musical', $event->getName());
        self::assertEquals('Ziggo Dome', $event->getVenue());
        self::assertEquals('Musical chat', $event->getChatNickname());
        self::assertEquals('https://example.org/photo.png', $event->getPhotoImageUrl());
        self::assertEquals('https://example.org/background.png', $event->getBackgroundImageUrl());
        self::assertEquals('#FFFFFF', $event->getChatMessageBackgroundColor());
        self::assertEquals('#000000', $event->getChatMessageTextColor());
        self::assertEquals('EUR', $event->getCurrency());
        self::assertEquals('Europe/Amsterdam', $event->getTimeZone());
        self::assertEquals('nl_NL', $event->getLocale());
        self::assertEquals(['CLUS1111111111', 'CLUS2222222222'], $event->getAdminUserIds());
    }

    /** @test */
    public function fall_back_to_no_admins_when_the_api_omits_them()
    {
        $this->givenAnAuthorisedClient();

        $payload = EventResponseFactory::create();
        unset($payload['admin_user_ids']);

        $this->mockClient
            ->on(
                new RequestMatcher('/events'),
                fn() => $this->mockResponse(['events' => [$payload]])
            );

        self::assertSame([], $this->givenSdk()->event()->getEvents()[0]->getAdminUserIds());
    }

    /** @test */
    public function return_an_empty_list_when_there_are_no_events()
    {
        $this->givenAnAuthorisedClient();

        $this->mockClient
            ->on(
                new RequestMatcher('/events'),
                fn() => $this->mockResponse(['events' => []])
            );

        self::assertSame([], $this->givenSdk()->event()->getEvents());
    }
}
