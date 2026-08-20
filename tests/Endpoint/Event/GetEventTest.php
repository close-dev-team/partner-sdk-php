<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class GetEventTest extends EndpointTestCase
{
    /** @test */
    public function call_the_single_event_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId),
                function (RequestInterface $request) use ($eventId) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals('/api/v1/events/' . $eventId, $request->getUri()->getPath());
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse(EventResponseFactory::create(['event_id' => (string)$eventId]));
                }
            );

        $event = $this->givenSdk()->event()->getEvent($eventId);

        self::assertEquals((string)$eventId, (string)$event->getEventId());
        self::assertEquals('The musical', $event->getName());
    }
}
