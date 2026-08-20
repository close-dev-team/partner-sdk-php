<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use DateTimeImmutable;
use DateTimeInterface;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class CloneEventTest extends EndpointTestCase
{
    /** @test */
    public function post_the_start_date_time_in_the_body_of_the_clone_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $startDateTime = new DateTimeImmutable('2026-01-01T10:00:00+01:00');
        $eventTime = new EventTime($startDateTime);

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/clone'),
                function (RequestInterface $request) use ($eventId, $startDateTime) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals('/api/v1/events/' . $eventId . '/clone', $request->getUri()->getPath());
                    self::assertEquals(
                        ['start_date_time' => $startDateTime->format(DateTimeInterface::W3C)],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse(EventResponseFactory::create(['event_id' => 'CLEV9999999999']));
                }
            );

        $event = $this->givenSdk()->event()->cloneEvent($eventId, $eventTime);

        self::assertEquals('CLEV9999999999', (string)$event->getEventId());
    }
}
