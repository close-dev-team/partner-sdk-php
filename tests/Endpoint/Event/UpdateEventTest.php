<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class UpdateEventTest extends EndpointTestCase
{
    /** @test */
    public function put_the_given_updates_on_the_event_endpoint()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');
        $updates = [
            'name' => 'The other musical',
            'venue' => 'Ahoy',
        ];

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId),
                function (RequestInterface $request) use ($eventId, $updates) {
                    self::assertEquals('PUT', $request->getMethod());
                    self::assertEquals('/api/v1/events/' . $eventId, $request->getUri()->getPath());
                    self::assertEquals(
                        $updates,
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse(EventResponseFactory::create($updates));
                }
            );

        $event = $this->givenSdk()->event()->updateEvent($eventId, $updates);

        self::assertEquals('The other musical', $event->getName());
        self::assertEquals('Ahoy', $event->getVenue());
    }
}
