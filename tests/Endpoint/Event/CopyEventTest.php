<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Event;

use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use ClosePartnerSdk\Tests\Factory\Response\EventResponseFactory;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class CopyEventTest extends EndpointTestCase
{
    /** @test */
    public function post_to_the_copy_endpoint_without_a_body()
    {
        $this->givenAnAuthorisedClient();
        $eventId = new EventId('CLEV1234567890');

        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $eventId . '/copy'),
                function (RequestInterface $request) use ($eventId) {
                    self::assertEquals('POST', $request->getMethod());
                    self::assertEquals('/api/v1/events/' . $eventId . '/copy', $request->getUri()->getPath());
                    self::assertEmpty($request->getBody()->getContents());

                    return $this->mockResponse(EventResponseFactory::create(['event_id' => 'CLEV9999999999']));
                }
            );

        $event = $this->givenSdk()->event()->copyEvent($eventId);

        self::assertEquals('CLEV9999999999', (string)$event->getEventId());
    }
}
